function debounce(func, wait, immediate) {
    var timeout;

    return function() {
        var context = this, args = arguments;
        var later = function() {
            timeout = null;
            if (!immediate) func.apply(context, args);
        };
        var callNow = immediate && !timeout;
        clearTimeout(timeout);
        timeout = setTimeout(later, wait);
        if (callNow) func.apply(context, args);
    };
};

/*
window.addEventListener('wheel', debounce(function(e) {
    slideAnim(event);
}, 90));
*/

//== Close offAsideMenuCanvas on resize
const asideMenu = document.getElementById('aside-menu');
if ( null != asideMenu ) {

    const offAsideMenuCanvas = new bootstrap.Offcanvas(asideMenu);

    window.addEventListener('resize', debounce( (e) => {
        offAsideMenuCanvas.hide();
    }, 40));
}

//== Avoid menu close onclick inside dropdown menu
const asideDropdown = document.querySelector('#aside-menu .dropdown-menu');
if ( asideDropdown != null ) {

    document.querySelector('#aside-menu .dropdown-menu').addEventListener('click', (e) => {
        e.stopPropagation();
    });
}

//== Sticky header (for animation)
function add_sticky_class_to_header() {

    const header = document.getElementById('header');

    if ( null != header ) {

        window.addEventListener('scroll', debounce( (e) => {

            if (window.pageYOffset > 0) {
                header.classList.add('is-sticky');
            } else {
                header.classList.remove('is-sticky');
            }
        }, 10));
    }
}
//add_sticky_class_to_header();

//== Sliders
const swiperLoader = {

    on: {
        afterInit: (e) => {
            e.el.classList.remove('swiper-is-loading');
        },
    },
}

const relatedProductsId = document.getElementById('related-products-slider');
if ( relatedProductsId != null ) {

    const relatedProductsSwiper = new Swiper(relatedProductsId, {
        slidesPerView: 1.4,
        spaceBetween: 15,
        allowTouchMove: true,
        watchOverflow: true,
        centeredSlidesBounds: true,
        lazyLoading: true,
        grabCursor: true,
        pagination: {
            el: '.products-slider-pagination',
            type: 'bullets',
            clickable: true,
        },
        breakpoints: {
            0: {
                slidesPerView: 1.3,
                spaceBetween: 15,
                centeredSlidesBounds: true,
            },
            569: {
                slidesPerView: 2,
                spaceBetween: 15,
                centeredSlidesBounds: false,
            },
            892: {
                slidesPerView: 3,

            }
        },
        ...swiperLoader
    });
}


//== Parallax
const banners = document.querySelectorAll('.wp-block-cover.is-style-has-parallax');

if ( banners ) {

    banners.forEach(banner => {
        banner.classList.add('jarallax');
        const bannerImg = banner.querySelector('.wp-block-cover__image-background');

        if ( bannerImg ) {
            bannerImg.classList.add('jarallax-img');

            jarallax(banner, {
                speed: 0.5,
                imgPosition: '50% 0',
            });
        }
    });
}
