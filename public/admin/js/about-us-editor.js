(() => {
    if (typeof tinymce === 'undefined') {
        return;
    }

    const scriptUrl = new URL(document.currentScript.src);
    const baseUrl = new URL('../vendors/tinymce', scriptUrl).pathname.replace(/\/$/, '');
    const fontUrl = new URL('../fonts/Vazirmatn.woff2', scriptUrl).href;

    document.querySelectorAll('[data-about-us-editor]').forEach((textarea) => {
        tinymce.init({
            target: textarea,
            base_url: baseUrl,
            suffix: '.min',
            license_key: 'gpl',
            directionality: textarea.dataset.direction || 'ltr',
            height: 520,
            min_height: 360,
            menubar: 'edit view insert format tools table help',
            plugins: [
                'advlist',
                'anchor',
                'autolink',
                'charmap',
                'code',
                'directionality',
                'fullscreen',
                'help',
                'image',
                'insertdatetime',
                'link',
                'lists',
                'media',
                'preview',
                'searchreplace',
                'table',
                'visualblocks',
                'wordcount',
            ],
            toolbar: [
                'undo redo | blocks | bold italic underline strikethrough',
                'alignleft aligncenter alignright alignjustify | bullist numlist outdent indent',
                'ltr rtl | link image media table | removeformat code fullscreen preview',
            ].join(' | '),
            toolbar_mode: 'sliding',
            promotion: false,
            branding: false,
            skin_url: `${baseUrl}/skins/ui/oxide`,
            content_css: `${baseUrl}/skins/content/default/content.min.css`,
            content_style: `
                @font-face {
                    font-family: "Vazirmatn";
                    src: url("${fontUrl}") format("woff2");
                    font-style: normal;
                    font-weight: 100 900;
                }

                body {
                    font-family: "Vazirmatn", Tahoma, sans-serif;
                    font-feature-settings: "ss01";
                    font-size: 14px;
                    line-height: 1.9;
                }
            `,
        });
    });

    document.querySelector('[data-about-us-form]')?.addEventListener('submit', () => {
        tinymce.triggerSave();
    });
})();
