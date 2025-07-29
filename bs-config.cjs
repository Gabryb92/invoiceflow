module.exports = {
    proxy: "http://127.0.0.1:8000",
    files: [
        "app/**/*.php",
        "resources/views/**/*.blade.php",
        "public/**/*.css",
        "resources/js/**/*.js",
    ],
    port: 3000,
    open: false,
    notify: true,
    ghostMode: {
        clicks: true,
        scroll: true,
        forms: true,
    },
};
