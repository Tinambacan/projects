$(document).ready(function () {
    function adjustFontSize(delta) {
        const fontSizeClasses = [
            "text-sm",
            "text-md",
            "text-lg",
            "text-xl",
            "text-2xl",
            "text-3xl",
            "text-4xl",
            "text-5xl",
        ];
        fontSizeClasses.forEach((sizeClass) => {
            $("." + sizeClass).each(function () {
                let currentFontSize =
                    parseFloat($(this).css("font-size")) || 16;
                $(this).css("font-size", currentFontSize + delta + "px");
            });
        });

        let currentFontSize = parseFloat($("#fontSize").css("font-size")) || 16;
        $("#fontSize").css("font-size", currentFontSize + delta + "px");

        localStorage.setItem("fontSize", currentFontSize + delta);
    }

    function setStoredFontSizeAdjustment() {
        let storedAdjustment =
            parseFloat(localStorage.getItem("fontSizeAdjustment")) || 0;

        let sliderValue = storedAdjustment;
        $("#text-size").val(sliderValue);
        adjustFontSize(storedAdjustment);
    }

    function resetFontSize() {
        const fontSizeClasses = [
            "text-sm",
            "text-md",
            "text-lg",
            "text-xl",
            "text-2xl",
            "text-3xl",
            "text-4xl",
            "text-5xl",
        ];

        fontSizeClasses.forEach((sizeClass) => {
            $("." + sizeClass).each(function () {
                $(this).css("font-size", "");
            });
        });

        $("#fontSize").css("font-size", "");
        localStorage.removeItem("fontSizeAdjustment");
        localStorage.removeItem("fontSize");
        $("#text-size").val(0);
    }

    setStoredFontSizeAdjustment();

    $("#text-size").on("input", function () {
        let sliderValue = parseInt($(this).val());

        let previousAdjustment =
            parseFloat(localStorage.getItem("fontSizeAdjustment")) || 0;
        let fontSizeAdjustment = sliderValue - previousAdjustment;

        adjustFontSize(fontSizeAdjustment);

        localStorage.setItem("fontSizeAdjustment", sliderValue);

        let currentFontSize = parseFloat($("#fontSize").css("font-size"));
        localStorage.setItem("fontSize", currentFontSize);

        // console.log("New font size:", currentFontSize);
    });

    $("#font-reset").click(function () {
        resetFontSize();
        localStorage.removeItem("fontSizeAdjustment");
        localStorage.removeItem("fontSize");
        $("#text-size").val(0);
    });

    function setDarkMode(isDark) {
        if (isDark) {
            $("html").addClass("dark");
            $("#dark-mode-toggle").prop("checked", true);
        } else {
            $("html").removeClass("dark");
            $("#dark-mode-toggle").prop("checked", false);
        }
        localStorage.setItem("darkMode", isDark);
    }

    const savedDarkMode = localStorage.getItem("darkMode");
    if (savedDarkMode !== null) {
        setDarkMode(savedDarkMode === "true");
    } else {
        // Use system preference for dark mode
        // const prefersDarkMode = window.matchMedia(
        //     "(prefers-color-scheme: dark)"
        // ).matches;
        // setDarkMode(prefersDarkMode);
    }

    $("#dark-mode-toggle").change(function () {
        setDarkMode(this.checked);
    });

    window
        .matchMedia("(prefers-color-scheme: dark)")
        .addEventListener("change", (e) => {
            setDarkMode(e.matches);
        });

    $("#web-settings").on("click", function () {
        $("#settings-content").toggleClass("hidden");
    });

    $(document).on("click", function (event) {
        if (
            !$(event.target).closest("#web-settings, #settings-content").length
        ) {
            $("#settings-content").addClass("hidden");
        }
    });
});
