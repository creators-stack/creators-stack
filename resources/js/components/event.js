window.addEventListener('scrollTop', () => {
    setTimeout(() => {
        window.scrollTo({
            top: 0,
            behavior: 'smooth',
        });
    }, 10)
});
