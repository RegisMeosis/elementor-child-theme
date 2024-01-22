$(document).ready(function () {
    /***************************************
        METTRE LE HERO A 100VH
    ***************************************/

    // const setHeroHeight = () => {
    //     const headerHeight = document.querySelector(
    //         ".elementor-location-header"
    //     ).offsetHeight;
    //     const hero = document.querySelector("#hero > .elementor-container");
    //     if (hero) {
    //         if (window.innerWidth > 1024) {
    //             hero.style.minHeight = `calc(100dvh - ${headerHeight}px)`;
    //         } else {
    //             hero.style.minHeight = "50dvh";
    //         }
    //     }
    // };
    // setHeroHeight();
    // window.addEventListener("resize", setHeroHeight);

    /***************************************
        SCROLL AUTO JUSQU'AU CONTENU
    ***************************************/

    // const scrollToContent = (margin) => {
    //     const offsetTop = document.querySelector("#main").offsetTop;
    //     scroll({
    //         top: offsetTop + margin,
    //         behavior: "smooth",
    //     });
    // };

    // if (!document.body.classList.contains("home") && window.innerWidth > 767) {
    //     scrollToContent(0);
    // }

    /***************************************
    INIT GSAP
    ***************************************/

    /*Aide => https://greensock.com/cheatsheet/*/

    gsap.registerPlugin(ScrollTrigger);
    gsap.config({ nullTargetWarn: false });

    /***************************************
    SMOOTH SCROLL (LENIS + GSAP)
    ***************************************/
    // const lenis = new Lenis();
    // lenis.on("scroll", ScrollTrigger.update);
    // gsap.ticker.add((time) => {
    //     lenis.raf(time * 1000);
    // });
    // gsap.ticker.lagSmoothing(0);

    /***************************************
        ANIMATIONS GSAP
    ***************************************/

    gsap.set("body:not(.elementor-editor-active) .transform-top", {
        opacity: 0,
        y: -100,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-left", {
        opacity: 0,
        x: -100,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-bottom", {
        opacity: 0,
        y: 100,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-right", {
        opacity: 0,
        x: 100,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-opacity", {
        opacity: 0,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-rotate-x", {
        rotation: 90,
        x: 200,
        opacity: 0,
    });
    gsap.set("body:not(.elementor-editor-active) .transform-path", {
        opacity: 0,
        clipPath: "polygon(0 0, 100% 0, 100% 0, 0 0);",
    });

    ScrollTrigger.batch(
        ".transform-left, .transform-right, .transform-top, .transform-bottom, .transform-opacity, .transform-rotate-x, .transform-path",
        {
            once: true,
            interval: 0,
            onEnter: (elements) => {
                gsap.to(elements, {
                    // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
                    opacity: 1,
                    rotate: 0,
                    x: 0,
                    y: 0,
                    stagger: 0.25,
                    duration: 1,
                    ease: Expo.easeOut,
                    //ease: Elastic.easeOut,
                    //ease: Bounce.easeOut,
                });
            },
            start: "top 75%",
        }
    );

    /***************************************
        ANIMATIONS GSAP LETTRE PAR LETTRE
    ***************************************/

    // const textsToSplit = Array.from(
    //     document.querySelectorAll(".animate-text .elementor-heading-title")
    // );
    // textsToSplit.forEach((textToSplit) => {
    //     const spans = textToSplit.innerText
    //         .split("")
    //         .map((letter) => `<span>${letter}</span>`)
    //         .join("");
    //     textToSplit.innerText = "";
    //     textToSplit.innerHTML = spans;
    // });

    // gsap.set(".animate-text .elementor-heading-title", {
    //     opacity: 1,
    // });
    // gsap.set(".animate-text .elementor-heading-title span", {
    //     opacity: 0,
    // });

    // ScrollTrigger.batch(".animate-text .elementor-heading-title span", {
    //     once: true,
    //     interval: 0,
    //     onEnter: (elements) => {
    //         gsap.to(elements, {
    //             // clipPath: "polygon(0 0, 100% 0, 100% 100%, 0 100%)",
    //             opacity: 1,
    //             rotate: 0,
    //             x: 0,
    //             y: 0,
    //             stagger: 0.075,
    //             duration: 0.05,
    //             //ease: Expo.easeOut,
    //             //ease: Elastic.easeOut,
    //             //ease: Bounce.easeOut,
    //         });
    //     },
    //     start: "top bottom-=250",
    // });
});
