<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class AdminController extends Controller
{
    // 表示用のラベル
    private const GENDERS = [
        1 => '男性',
        2 => '女性',
        3 => 'その他',
    ];

    // お問い合わせ種類（要件の5つ）
    private const CATEGORIES = [
        1 => '商品のお届けについて',
        2 => '商品の交換について',
        3 => '商品トラブル',
        4 => 'ショップへのお問い合わせ',
        5 => 'その他',
    ];

    public function index(Request $request)
    {
        $query = $this->buildFilteredQuery($request);

        // 7件ごとにページネーション
        $contacts = $query->orderByDesc('created_at')
            ->paginate(7)
            ->appends($request->query()); // 検索条件をページングに引き継ぐ

        return view('admin', [
            'contacts' => $contacts,
            'genders' => self::GENDERS,
            'categories' => self::CATEGORIES,
            'filters' => $request->only(['keyword', 'email', 'gender', 'category', 'date']),
        ]);
    }

    // モーダル詳細（JSON返却）
    public function show(Contact $contact)
    {
        return response()->json([
            'id' => $contact->id,
            'name' => trim($contact->last_name . ' ' . $contact->first_name),
            'gender' => self::GENDERS[$contact->gender] ?? '不明',
            'email' => $contact->email,
            'tel' => $contact->tel,
            'address' => $contact->address,
            'building' => $contact->building,
            'category' => self::CATEGORIES[$contact->category] ?? '不明',
            'detail' => $contact->detail,
        ]);
    }

    // 削除
    public function destroy(Contact $contact)
    {
        $contact->delete();

        return redirect()
            ->route('admin', request()->query())
            ->with('success', '削除しました');
    }

    // CSVエクスポート（今表示中＝検索条件で絞り込まれた一覧）
    public function export(Request $request)
    {
        $contacts = $this->buildFilteredQuery($request)
            ->orderByDesc('created_at')
            ->get();

        $filename = 'contacts_' . now()->format('Ymd_His') . '.csv';

        $headers = [
            'Content-Type' => 'text/csv; charset=UTF-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
        ];

        $callback = function () use ($contacts) {
            $out = fopen('php://output', 'w');

            // Excel文字化け対策（UTF-8 BOM）
            fwrite($out, "\xEF\xBB\xBF");

            // ヘッダ
            fputcsv($out, ['お名前', '性別', 'メールアドレス', 'お問い合わせの種類', 'お問い合わせ内容', '作成日']);

            foreach ($contacts as $c) {
                fputcsv($out, [
                    trim($c->last_name . ' ' . $c->first_name),
                    self::GENDERS[$c->gender] ?? '',
                    $c->email,
                    self::CATEGORIES[$c->category] ?? '',
                    $c->detail,
                    optional($c->created_at)->format('Y-m-d'),
                ]);
            }

            fclose($out);
        };

        return Response::stream($callback, 200, $headers);
    }

    // 検索条件を適用したQueryを共通化
    private function buildFilteredQuery(Request $request)
    {
        $q = Contact::query();

        // 1) 名前（姓/名/フルネーム、完全一致/部分一致）
        // keywordに「名前 or メール」を入れるUIでも良いが、要件が分かれているので email も別対応可能にしておく
        $keyword = trim((string) $request->input('keyword', ''));
        if ($keyword !== '') {
            // 全角スペースを半角へ
            $normalized = str_replace('　', ' ', $keyword);
            $parts = array_values(array_filter(explode(' ', $normalized)));

            $q->where(function ($sub) use ($keyword, $parts) {
                // 部分一致（姓・名）
                $sub->where('last_name', 'like', "%{$keyword}%")
                    ->orWhere('first_name', 'like', "%{$keyword}%");

                // フルネーム（スペースなし/あり）部分一致
                $sub->orWhereRaw("CONCAT(last_name, first_name) LIKE ?", ["%{$keyword}%"])
                    ->orWhereRaw("CONCAT(last_name, ' ', first_name) LIKE ?", ["%{$keyword}%"]);

                // 「姓 名」で来た場合は両方含む検索（部分一致）
                if (count($parts) >= 2) {
                    $last = $parts[0];
                    $first = $parts[1];
                    $sub->orWhere(function ($w) use ($last, $first) {
                        $w->where('last_name', 'like', "%{$last}%")
                          ->where('first_name', 'like', "%{$first}%");
                    });
                }
            });
        }

        // 2) メールアドレス（完全一致/部分一致）
        $email = trim((string) $request->input('email', ''));
        if ($email !== '') {
            $q->where('email', 'like', "%{$email}%");
        }

        // 3) 性別（デフォルト「性別」 / 全て・男性・女性・その他）
        $gender = $request->input('gender');
        // ''(未選択) or 'all' は絞り込みなし
        if ($gender !== null && $gender !== '' && $gender !== 'all') {
            $q->where('gender', (int) $gender);
        }

        // 4) お問い合わせ種類（1〜5）
        $category = $request->input('category');
        if ($category !== null && $category !== '' && $category !== 'all') {
            $q->where('category', (int) $category);
        }

        // 5) 日付（カレンダー input）
        $date = $request->input('date');
        if ($date !== null && $date !== '') {
            $q->whereDate('created_at', $date);
        }

        return $q;
    }
}