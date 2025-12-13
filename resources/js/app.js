import './bootstrap';

import Alpine from 'alpinejs';
window.Alpine = Alpine;

const root = document.documentElement;

const applyTheme = (theme) => {
    if (theme === 'dark') root.classList.add('dark');
    else root.classList.remove('dark');
};

const withTransition = (fn) => {
    // tambahkan class transition sementara
    root.classList.add('theme-transition');
    fn();

    // lepas class setelah animasi selesai
    window.setTimeout(() => {
        root.classList.remove('theme-transition');
    }, 250);
};

// initial theme
const savedTheme = localStorage.getItem('theme') || 'light';
applyTheme(savedTheme);

// global toggle
window.toggleTheme = () => {
    const isDark = root.classList.contains('dark');
    const next = isDark ? 'light' : 'dark';

    localStorage.setItem('theme', next);
    withTransition(() => applyTheme(next));
};

Alpine.start();
