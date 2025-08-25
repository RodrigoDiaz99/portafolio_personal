// Registrar el plugin ScrollTrigger
gsap.registerPlugin(ScrollTrigger);

document.addEventListener('DOMContentLoaded', function () {      
    function lazyLoad() {

        var lazyImages = document.querySelectorAll('img[data-src]');

        lazyImages.forEach(function(img) {
            if (img.getBoundingClientRect().top <= window.innerHeight && img.getBoundingClientRect().bottom >= 0 && getComputedStyle(img).display !== 'none') {
            img.setAttribute('src', img.getAttribute('data-src'));
            img.removeAttribute('data-src');
            img.classList.remove('lazy');
            }
        });

        // Elimina los listeners una vez que todas las imágenes se han cargado
        if (lazyImages.length === 0) {
            window.removeEventListener('scroll', lazyLoad);
            window.removeEventListener('resize', lazyLoad);
            window.removeEventListener('orientationchange', lazyLoad);
        }
    }


    // Agrega los listeners de eventos para activar lazyLoad en diferentes situaciones
    window.addEventListener('scroll', lazyLoad);
    window.addEventListener('resize', lazyLoad);
    window.addEventListener('orientationchange', lazyLoad);

    // Inicia lazyLoad cuando se carga la página
    window.addEventListener('load', lazyLoad);
    
    // Detectar ruta del blog y aplicar estilos al nav
    function updateNavbarStyle() {
        const navbar = document.getElementById('navbar');
        if (!navbar) return;

        const isBlogPage = window.location.pathname.includes('/blog');
        if (isBlogPage) {
            navbar.classList.add('blog-nav');

        } else {
            navbar.classList.remove('blog-nav');
        }
    }

    // Ejecutar al cargar y cuando cambia la URL
    updateNavbarStyle();
    window.addEventListener('popstate', updateNavbarStyle);

    // Animación inicial del header
    gsap.from("#main-title", {
        duration: 1.5,
        y: 50,
        opacity: 0,
        ease: "power3.out"
    });

    gsap.from("#tagline", {
        duration: 1.5,
        y: 50,
        opacity: 0,
        delay: 0.3,
        ease: "power3.out"
    });

    // Configuración inicial del botón CTA (visible por defecto)
    gsap.set("#cta-button", {
        opacity: 1,
        y: 0
    });

    // Animación de entrada del botón CTA (solo si es necesario)
    if (!document.querySelector("#cta-button").classList.contains('no-animate')) {
        gsap.from("#cta-button", {
            duration: 1.5,
            y: 50,
            opacity: 0,
            delay: 0.6,
            ease: "power3.out"
        });
    }

    // Paralaje en el header (fondo)
    gsap.to(".header-bg", {
        yPercent: 20, // Mueve el fondo 20% de su altura al hacer scroll
        ease: "none",
        scrollTrigger: {
            trigger: "header",
            start: "top top",
            end: "bottom top",
            scrub: true, // Animación suave al hacer scroll
        }
    });

    // Animaciones al hacer scroll para cada sección
    gsap.utils.toArray(".section").forEach(section => {
        // Asegurarse de que los elementos de la sección estén ocultos inicialmente
        gsap.set(section, {
            opacity: 0,
            y: 80
        });

        gsap.to(section, {
            scrollTrigger: {
                trigger: section,
                start: "top 75%", // Inicia cuando el 75% superior de la sección entra en vista
                end: "bottom 25%", // Termina cuando el 25% inferior de la sección sale de vista
                toggleActions: "play none none reverse", // Play al entrar, reverse al salir
                //markers: true // Útil para depurar, eliminar en producción
            },
            opacity: 1,
            y: 0,
            duration: 1.2,
            ease: "power3.out"
        });
    });

    // Animación específica para el perfil
    // Asegurarse de que la imagen y el texto estén ocultos inicialmente
    gsap.set(".profile-img", {
        x: -100,
        opacity: 0,
        rotateZ: -10
    });
    gsap.set(".profile-text", {
        x: 100,
        opacity: 0
    });

    gsap.to(".profile-img", {
        scrollTrigger: {
            trigger: "#perfil",
            start: "top 60%",
            toggleActions: "play none none reverse"
        },
        x: 0,
        opacity: 1,
        rotateZ: 0, // Un poco más de rotación
        duration: 1.5,
        ease: "elastic.out(1, 0.5)" // Efecto más elástico
    });

    gsap.to(".profile-text", {
        scrollTrigger: {
            trigger: "#perfil",
            start: "top 60%",
            toggleActions: "play none none reverse"
        },
        x: 0,
        opacity: 1,
        duration: 1.5,
        ease: "power3.out",
        delay: 0.3
    });

    // Animación de la línea de tiempo (Educación)
    gsap.utils.toArray(".timeline-item").forEach((item, i) => {
        // Asegurarse de que el elemento esté oculto inicialmente
        gsap.set(item, {
            opacity: 0,
            x: i % 2 === 0 ? -150 : 150
        });

        gsap.to(item, {
            scrollTrigger: {
                trigger: item,
                start: "top 85%",
                toggleActions: "play none none reverse"
            },
            x: 0, // Animación a su posición original
            opacity: 1,
            duration: 1,
            delay: i * 0.1, // Retraso escalonado
            ease: "power2.out"
        });
    });

    // Animación de las cards de experiencia
    gsap.utils.toArray(".experience-card").forEach((card, i) => {
        // Asegurarse de que la tarjeta esté oculta inicialmente
        gsap.set(card, {
            opacity: 0,
            y: 100
        });

        gsap.to(card, {
            scrollTrigger: {
                trigger: card,
                start: "top 80%",
                toggleActions: "play none none reverse"
            },
            y: 0,
            opacity: 1,
            duration: 1,
            delay: i * 0.15,
            ease: "back.out(1.2)"
        });
    });

    // Animación de las categorías de habilidades
    gsap.utils.toArray(".skill-category").forEach((category, i) => {
        // Asegurarse de que la categoría esté oculta inicialmente
        gsap.set(category, {
            opacity: 0,
            scale: 0.8
        });

        gsap.to(category, {
            scrollTrigger: {
                trigger: category,
                start: "top 80%",
                toggleActions: "play none none reverse"
            },
            scale: 1,
            opacity: 1,
            duration: 1,
            delay: i * 0.1,
            ease: "power3.out"
        });
    });

    // Animación de las barras de habilidades
    gsap.utils.toArray(".skill-progress").forEach(bar => {
        const width = bar.style.width; // Obtener el ancho final del estilo inline
        bar.style.width = "0%"; // Reiniciar el ancho para la animación

        gsap.to(bar, {
            scrollTrigger: {
                trigger: bar,
                start: "top 85%",
                toggleActions: "play none none reverse"
            },
            width: width, // Animar hasta el ancho original
            duration: 1.8,
            ease: "power4.out"
        });
    });

    // Animación de los íconos de redes sociales
    gsap.utils.toArray(".social-link").forEach((link, i) => {
        // Asegurarse de que el enlace esté oculto inicialmente
        gsap.set(link, {
            opacity: 0,
            y: 50
        });

        gsap.to(link, {
            scrollTrigger: {
                trigger: "#redes",
                start: "top 70%",
                toggleActions: "play none none reverse"
            },
            y: 0,
            opacity: 1,
            duration: 0.8,
            delay: i * 0.1,
            ease: "back.out(1.7)"
        });
    });

    // Efecto de navbar al hacer scroll
    window.addEventListener('scroll', function () {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 100) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });

    // Smooth scrolling para los enlaces
    document.querySelectorAll('a[href^="#"]').forEach(anchor => {
        anchor.addEventListener('click', function (e) {
            const targetId = this.getAttribute('href');
            const targetElement = document.querySelector(targetId);

            if (targetElement) {
                // Solo prevenir default si es un enlace interno distinto a #
                if (targetId !== '#') {
                    e.preventDefault();
                }

                // Usar scrollIntoView como fallback si GSAP falla
                try {
                    // Animación más rápida con menor duración
                    gsap.to(window, {
                        duration: 0.3, // Reducido de 0.8 a 0.3 segundos
                        scrollTo: {
                            y: targetElement.offsetTop - 80,
                            autoKill: true
                        },
                        ease: "power1.out", // Ease más rápido
                        onComplete: () => {
                            window.scrollTo(0, targetElement.offsetTop - 80);
                        }
                    });
                } catch (e) {
                    targetElement.scrollIntoView({
                        behavior: 'smooth',
                        block: 'start'
                    });
                }
            }
        });
    });

    // Funcionalidad del menú móvil
    const menuToggle = document.getElementById('mobile-menu');
    const mobileMenu = document.querySelector('.nav-links'); // Usamos la clase existente del menú
    const navLinks = document.querySelectorAll('.mobile-menu a');

    if (menuToggle && mobileMenu) {
        menuToggle.addEventListener('click', function () {
            menuToggle.classList.toggle('active');
            mobileMenu.classList.toggle('active');
            document.body.classList.toggle('no-scroll');
        });

        // Cerrar menú al hacer click en un enlace
        navLinks.forEach(link => {
            link.addEventListener('click', function () {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.classList.remove('no-scroll');
            });
        });

        // Cerrar menú al hacer click fuera
        document.addEventListener('click', function (e) {
            if (!mobileMenu.contains(e.target) && !menuToggle.contains(e.target)) {
                menuToggle.classList.remove('active');
                mobileMenu.classList.remove('active');
                document.body.classList.remove('no-scroll');
            }
        });
    }

   
    
});