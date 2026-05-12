(function () {
    const shell = document.getElementById('vue-layout-shell');
    const status = document.getElementById('vue-navigation-status');

    if (!shell || !status || !window.Vue) {
        return;
    }

    const fastPaths = [
        /^\/$/,
        /^\/about\/?$/,
        /^\/shop\/?$/,
        /^\/shop\/[^/]+\/?$/,
        /^\/categories\/?$/,
        /^\/category\/[^/]+\/?$/,
    ];

    const fullReloadPaths = [
        /^\/admin(\/|$)/,
        /^\/seller(\/|$)/,
        /^\/customer(\/|$)/,
        /^\/cart(\/|$)/,
        /^\/checkout(\/|$)/,
        /^\/login\/?$/,
        /^\/register\/?$/,
    ];

    const pageScriptContainerId = 'vue-runtime-page-scripts';
    const headStartMarker = 'vue-page-head-start';
    const headEndMarker = 'vue-page-head-end';

    function normalizePath(pathname) {
        return pathname.replace(/\/+$/, '') || '/';
    }

    function canUseVueNavigation(url) {
        const path = normalizePath(url.pathname);

        if (fullReloadPaths.some((pattern) => pattern.test(path))) {
            return false;
        }

        return fastPaths.some((pattern) => pattern.test(path));
    }

    function isMarker(node, marker) {
        return node.nodeType === Node.COMMENT_NODE && node.nodeValue.trim() === marker;
    }

    function getNodesBetweenMarkers(root) {
        const nodes = Array.from(root.childNodes);
        const startIndex = nodes.findIndex((node) => isMarker(node, headStartMarker));
        const endIndex = nodes.findIndex((node) => isMarker(node, headEndMarker));

        if (startIndex === -1 || endIndex === -1 || endIndex <= startIndex) {
            return [];
        }

        return nodes.slice(startIndex + 1, endIndex);
    }

    function markInitialPageHead() {
        getNodesBetweenMarkers(document.head).forEach((node) => {
            if (node.nodeType === Node.ELEMENT_NODE) {
                node.dataset.vuePageHead = 'true';
            }
        });
    }

    function syncPageHead(doc) {
        document.querySelectorAll('[data-vue-page-head="true"]').forEach((node) => node.remove());

        const nextNodes = getNodesBetweenMarkers(doc.head);
        const endMarker = Array.from(document.head.childNodes).find((node) => isMarker(node, headEndMarker));

        nextNodes.forEach((node) => {
            if (node.nodeType !== Node.ELEMENT_NODE) {
                return;
            }

            const clone = node.cloneNode(true);
            clone.dataset.vuePageHead = 'true';

            if (endMarker) {
                document.head.insertBefore(clone, endMarker);
            } else {
                document.head.appendChild(clone);
            }
        });
    }

    function runPageScripts(doc) {
        const oldScripts = document.getElementById(pageScriptContainerId);
        if (oldScripts) {
            oldScripts.remove();
        }

        const scriptHost = document.createElement('div');
        scriptHost.id = pageScriptContainerId;
        scriptHost.hidden = true;
        document.body.appendChild(scriptHost);

        const scripts = [
            ...doc.querySelectorAll('[data-vue-page] script'),
            ...doc.querySelectorAll('[data-vue-page-scripts] script'),
        ];

        scripts.forEach((script) => {
            const nextScript = document.createElement('script');

            Array.from(script.attributes).forEach((attribute) => {
                nextScript.setAttribute(attribute.name, attribute.value);
            });

            nextScript.textContent = script.textContent;
            scriptHost.appendChild(nextScript);
        });

        window.setTimeout(() => {
            document.dispatchEvent(new Event('DOMContentLoaded', {
                bubbles: true,
                cancelable: true,
            }));
        }, 0);
    }

    function setActiveLinks(url) {
        const currentPath = normalizePath(url.pathname);

        document.querySelectorAll('.nav-link, .mobile-nav-link, .footer-link, .footer-bottom-link').forEach((link) => {
            if (!link.href) {
                return;
            }

            const linkUrl = new URL(link.href, window.location.origin);
            const linkPath = normalizePath(linkUrl.pathname);
            const isActive = currentPath === linkPath || (linkPath !== '/' && currentPath.startsWith(linkPath + '/'));

            link.classList.toggle('active', isActive);
        });
    }

    function closeMobileNav() {
        const mobileNav = document.getElementById('mobileNav');

        if (mobileNav) {
            mobileNav.classList.remove('active');
        }

        document.body.style.overflow = '';
    }

    const app = Vue.createApp({
        data() {
            return {
                loading: false,
                currentController: null,
            };
        },

        mounted() {
            document.addEventListener('click', this.handleClick);
            document.addEventListener('submit', this.handleSubmit, true);
            window.addEventListener('popstate', this.handlePopState);
            window.VueLayout = {
                visit: this.visit,
            };
        },

        watch: {
            loading(value) {
                shell.classList.toggle('is-loading', value);
            },
        },

        beforeUnmount() {
            document.removeEventListener('click', this.handleClick);
            document.removeEventListener('submit', this.handleSubmit, true);
            window.removeEventListener('popstate', this.handlePopState);
        },

        methods: {
            shouldIgnoreClick(event, link) {
                if (
                    event.defaultPrevented ||
                    event.button !== 0 ||
                    event.metaKey ||
                    event.ctrlKey ||
                    event.shiftKey ||
                    event.altKey ||
                    link.target && link.target !== '_self' ||
                    link.hasAttribute('download') ||
                    link.dataset.noVue !== undefined
                ) {
                    return true;
                }

                const href = link.getAttribute('href') || '';

                if (
                    href.startsWith('#') ||
                    href.startsWith('mailto:') ||
                    href.startsWith('tel:') ||
                    href.startsWith('javascript:')
                ) {
                    return true;
                }

                const url = new URL(link.href, window.location.origin);

                if (url.origin !== window.location.origin || !canUseVueNavigation(url)) {
                    return true;
                }

                return url.pathname === window.location.pathname &&
                    url.search === window.location.search &&
                    url.hash !== '';
            },

            handleClick(event) {
                const link = event.target.closest('a[href]');

                if (!link || this.shouldIgnoreClick(event, link)) {
                    return;
                }

                event.preventDefault();
                this.visit(link.href);
            },

            handleSubmit(event) {
                const form = event.target;
                const method = (form.getAttribute('method') || 'GET').toUpperCase();

                if (
                    method !== 'GET' ||
                    form.target && form.target !== '_self' ||
                    form.dataset.noVue !== undefined
                ) {
                    return;
                }

                const url = new URL(form.getAttribute('action') || window.location.href, window.location.origin);

                if (url.origin !== window.location.origin || !canUseVueNavigation(url)) {
                    return;
                }

                const formData = new FormData(form);
                url.search = '';

                formData.forEach((value, key) => {
                    if (value !== '') {
                        url.searchParams.append(key, value);
                    }
                });

                event.preventDefault();
                this.visit(url.href);
            },

            handlePopState() {
                this.visit(window.location.href, {
                    push: false,
                    scroll: false,
                });
            },

            async visit(href, options = {}) {
                const settings = {
                    push: true,
                    scroll: true,
                    ...options,
                };
                const url = new URL(href, window.location.origin);

                if (!canUseVueNavigation(url)) {
                    window.location.href = url.href;
                    return;
                }

                if (this.currentController) {
                    this.currentController.abort();
                }

                this.currentController = new AbortController();
                this.loading = true;
                closeMobileNav();

                try {
                    const response = await fetch(url.href, {
                        credentials: 'same-origin',
                        headers: {
                            'X-Requested-With': 'Vue-Navigation',
                            'Accept': 'text/html, application/xhtml+xml',
                        },
                        signal: this.currentController.signal,
                    });

                    const contentType = response.headers.get('content-type') || '';

                    if (!response.ok || !contentType.includes('text/html')) {
                        window.location.href = url.href;
                        return;
                    }

                    const html = await response.text();
                    const doc = new DOMParser().parseFromString(html, 'text/html');
                    const nextPage = doc.querySelector('[data-vue-page]');
                    const currentPage = document.querySelector('[data-vue-page]');

                    if (!nextPage || !currentPage) {
                        window.location.href = url.href;
                        return;
                    }

                    syncPageHead(doc);
                    currentPage.innerHTML = nextPage.innerHTML;
                    document.title = doc.title || document.title;

                    if (settings.push) {
                        history.pushState({}, '', url.href);
                    }

                    setActiveLinks(url);
                    runPageScripts(doc);

                    if (settings.scroll) {
                        window.scrollTo({
                            top: 0,
                            behavior: 'smooth',
                        });
                    }
                } catch (error) {
                    if (error.name !== 'AbortError') {
                        window.location.href = url.href;
                    }
                } finally {
                    this.loading = false;
                    this.currentController = null;
                }
            },
        },
    });

    markInitialPageHead();
    app.mount(status);
})();
