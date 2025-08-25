<!-- resources/views/components/sort-select.blade.php -->
<div class="sort-area">
  <span>並べ替え</span>
  <select onchange="updateSort(this.value)">
    <option value="year_new_created" {{ request('sort', 'year_new_created') == 'year_new_created' ? 'selected' : '' }}>標準（年式・入庫日順）</option>
    <option value="newest" {{ request('sort') == 'newest' ? 'selected' : '' }}>新着：新しい順</option>
    <option value="oldest" {{ request('sort') == 'oldest' ? 'selected' : '' }}>新着：古い順</option>
    <option value="total_low" {{ request('sort') == 'total_low' ? 'selected' : '' }}>支払総額：安い順</option>
    <option value="total_high" {{ request('sort') == 'total_high' ? 'selected' : '' }}>支払総額：高い順</option>
    <!-- <option value="price_low" {{ request('sort') == 'price_low' ? 'selected' : '' }}>本体価格：安い順</option>
    <option value="price_high" {{ request('sort') == 'price_high' ? 'selected' : '' }}>本体価格：高い順</option> -->
    <option value="year_new" {{ request('sort') == 'year_new' ? 'selected' : '' }}>年式：新しい順</option>
    <option value="year_old" {{ request('sort') == 'year_old' ? 'selected' : '' }}>年式：古い順</option>
    <option value="mileage_low" {{ request('sort') == 'mileage_low' ? 'selected' : '' }}>走行距離：少ない順</option>
    <option value="mileage_high" {{ request('sort') == 'mileage_high' ? 'selected' : '' }}>走行距離：多い順</option>
    <option value="cc_low" {{ request('sort') == 'cc_low' ? 'selected' : '' }}>排気量：少ない順</option>
    <option value="cc_high" {{ request('sort') == 'cc_high' ? 'selected' : '' }}>排気量：多い順</option>
  </select>
</div>


<script>
function updateSort(sortValue) {
  // 現在のURLを取得してsortパラメータを追加・変更
  const currentUrl = new URL(window.location.href);
  
  if (sortValue && sortValue !== 'year_new_created') {
    currentUrl.searchParams.set('sort', sortValue);
  } else {
    // デフォルトの場合はsortパラメータを削除
    currentUrl.searchParams.delete('sort');
  }
  
  // 他のパラメータを保持してURLを更新
  window.location.href = currentUrl.toString();
}
</script>