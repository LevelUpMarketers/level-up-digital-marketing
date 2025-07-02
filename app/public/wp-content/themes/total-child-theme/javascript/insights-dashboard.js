document.addEventListener("DOMContentLoaded", function () {
    // Select all buttons with the class 'cm-dash-services-button'
    const serviceButtons = document.querySelectorAll(".lur-services-website-design-button");

    serviceButtons.forEach(button => {
        button.addEventListener("click", function () {
            // Remove 'active' class from all buttons in the same group
            serviceButtons.forEach(btn => btn.classList.remove("active"));

            // Add 'active' class to the clicked button
            this.classList.add("active");
        });
    });
});


document.addEventListener("DOMContentLoaded", function () {
    // Select all left menu buttons
    const buttons = document.querySelectorAll(".lur-services-website-design-button");

    // Select all right info containers
    const contentContainers = document.querySelectorAll(".cm-right-info-inner-content-type-holder");

    // Select the element to scroll to
    const scrollToElement = document.querySelector(".cm-indiv-left-button-indiv-top.cm-dash-intro-container");

    buttons.forEach(button => {
        button.addEventListener("click", function () {
            // Find the button's unique class (e.g., "cm-dash-monthlyactivity-button")
            let buttonClasses = Array.from(this.classList);
            let uniqueButtonClass = buttonClasses.find(cls => cls.startsWith("cm-dash-"));

            if (!uniqueButtonClass) return; // Exit if no unique class found

            // Determine the corresponding content class
            let correspondingContentClass = uniqueButtonClass.replace("cm-dash-", "cm-right-info-inner-content-type-holder-");
            let correspondingContent = document.querySelector("." + correspondingContentClass);

            if (!correspondingContent) return; // Exit if no matching content found

            // Hide currently active content (if any)
            contentContainers.forEach(container => {
                if (container !== correspondingContent && container.classList.contains("active")) {
                    container.style.opacity = "0";
                    container.style.height = "0px";
                    setTimeout(() => {
                        container.style.display = "none";
                        container.classList.remove("active");
                    }, 300); // Match transition duration
                }
            });

            // Show the new corresponding content
            correspondingContent.style.display = "block";
            setTimeout(() => {
                correspondingContent.style.height = "auto";
                correspondingContent.style.opacity = "1";
                correspondingContent.classList.add("active");
            }, 10); // Small delay to trigger transition

            // Scroll to the top of the intro container smoothly with a 300px offset
            if (scrollToElement) {
                const yOffset = -300; // Offset of 300px
                const yPosition = scrollToElement.getBoundingClientRect().top + window.scrollY + yOffset;

                window.scrollTo({
                    top: yPosition,
                    behavior: "smooth"
                });
            }
        });
    });
});
