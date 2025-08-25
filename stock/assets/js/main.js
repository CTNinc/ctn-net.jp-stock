const hamburger = document.getElementById("hamburger");
const spNav = document.getElementById("sp-nav");
const closeBtn = document.getElementById("menu-close");

// ハンバーガークリック
hamburger.addEventListener("click", () => {
    const isOpen = spNav.classList.toggle("open");
    hamburger.classList.toggle("active", isOpen);
});

// 「メニューを閉じる」ボタン
closeBtn.addEventListener("click", () => {
    spNav.classList.remove("open");
    hamburger.classList.remove("active");
});

//SPエリアアコーディオン
document.addEventListener('DOMContentLoaded', function () {
  const items = document.querySelectorAll('.area-item');

  items.forEach(item => {
    const toggle = item.querySelector('.area-region-toggle');
    const button = item.querySelector('.toggle-button');

    toggle.addEventListener('click', () => {
      const isActive = item.classList.toggle('active');
      button.textContent = isActive ? '−' : '＋';
      button.setAttribute('aria-expanded', isActive);
    });
  });
});

document.addEventListener("DOMContentLoaded", function () {
    const targets = document.querySelectorAll(".number-flex");

    if (window.innerWidth >= 768) {
        // 768px以上をPCとみなす
        targets.forEach(function (target) {
            target.classList.remove("scroll");
            target.removeAttribute("data-simplebar");
        });
    }
});


// 車種一覧絞り込み開閉

// document.addEventListener('DOMContentLoaded', function () {
//   const closeButton = document.querySelector('.close-button');
//   let folded = true;

//   // data属性から画像URLを取得（Bladeですでに評価済み）
//   const arrowUp = closeButton.dataset.arrowUp;
//   const arrowDown = closeButton.dataset.arrowDown;

//   // 初期表示（折りたたみ状態）
//   closeButton.innerHTML = `<img src="${arrowDown}" alt="更に詳細な絞り込み">更に詳細な絞り込み`;

//   closeButton.addEventListener('click', function () {
//     const lines = document.querySelectorAll('.search-form .line.foldable');
//     lines.forEach((line) => {
//       line.classList.toggle('expanded');
//     });

//     folded = !folded;
//     closeButton.innerHTML = folded
//       ? `<img src="${arrowDown}" alt="更に詳細な絞り込み">更に詳細な絞り込み`
//       : `<img src="${arrowUp}" alt="閉じる">閉じる`;
//   });
// });

// スマホ用開閉
  document.addEventListener('DOMContentLoaded', function () {
    const filterToggle = document.getElementById('filterToggle');
    const filterOverlay = document.getElementById('filterOverlay');
    const filterClose = document.getElementById('filterClose');

    filterToggle.addEventListener('click', function () {
      filterOverlay.classList.add('active');
      document.body.style.overflow = 'hidden'; // スクロール禁止
    });

    filterClose.addEventListener('click', function () {
      filterOverlay.classList.remove('active');
      document.body.style.overflow = ''; // スクロール解除
    });

    // 背景クリックで閉じる（オプション）
    filterOverlay.addEventListener('click', function (e) {
      if (e.target === filterOverlay) {
        filterOverlay.classList.remove('active');
        document.body.style.overflow = '';
      }
    });
  });



  document.addEventListener("DOMContentLoaded", function() {
    const toggleBtn = document.getElementById("toggleAdvanced");
    const closeBtn = document.getElementById("closeAdvanced");
    const advancedBox = document.querySelector(".search-advanced");

    toggleBtn.addEventListener("click", function() {
        advancedBox.style.display = "block";
        toggleBtn.style.display = "none"; 
    });

    closeBtn.addEventListener("click", function() {
        advancedBox.style.display = "none";
        toggleBtn.style.display = "inline-block"; 
    });
});



function addToRecentlyViewed(vehicleId) {
    let viewed = JSON.parse(localStorage.getItem('recently_viewed')) || [];
    // Loại trùng
    viewed = viewed.filter(id => id !== vehicleId);
    viewed.unshift(vehicleId); // Thêm vào đầu
    if (viewed.length > 10) viewed.pop(); // Giới hạn 10 xe
    localStorage.setItem('recently_viewed', JSON.stringify(viewed));
}