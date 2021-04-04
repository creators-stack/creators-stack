export default class LightGallery {
    constructor() {
        this.lg_data = null;

        this.initGallery();

        window.addEventListener('initGallery', () => {
            this.initGallery();
        });
    }

    initGallery() {
        const lg = document.getElementById('lightgallery');

        /**
         * destroying previous instance
         */
        if (self.lg_data) {
            self.lg_data.destroy(true);
        }

        /**
         * Creating lightGallery instance
         */
        if (lg) {
            lightGallery(lg, {
                progressBar: false,
            });

            self.lg_data = window.lgData[lg.getAttribute('lg-uid')];
        }
    }
}
