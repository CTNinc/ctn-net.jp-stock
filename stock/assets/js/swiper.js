// 各Swiperインスタンス保持用
const swiperInstances = {};

// 初期化関数（クラス名を受け取る）
function initResponsiveSwiper(className) {
  const isMobile = window.innerWidth <= 768;
  const instanceKey = className.replace('.', '');
  const swiperEl = document.querySelector(className);

  // スマホ：Swiperを初期化
  if (isMobile && !swiperInstances[instanceKey]) {
    swiperInstances[instanceKey] = new Swiper(className, {
      slidesPerView: 1,
      spaceBetween: 16,
      pagination: {
        el: `${className} .swiper-pagination`,
        clickable: true,
        dynamicBullets: true,
      },
    });
  }

  // PC：Swiperを破棄
  if (!isMobile && swiperInstances[instanceKey]) {
    swiperInstances[instanceKey].destroy(true, true);
    swiperInstances[instanceKey] = null;

    swiperEl?.classList.remove(
      'swiper-initialized',
      'swiper-horizontal',
      'swiper-backface-hidden'
    );
  }
}

// 初期化対象のSwiperクラス一覧
const swiperTargets = ['.commons-swiper', '.history-swiper', '.recommended-swiper'];

// イベント登録（ロード時・リサイズ時）
function handleSwipers() {
  swiperTargets.forEach(className => initResponsiveSwiper(className));
}

window.addEventListener('load', handleSwipers);
window.addEventListener('resize', handleSwipers);
