document.addEventListener("DOMContentLoaded", () => {
    const elements = document.querySelectorAll(".flip-words-wrapper");

    elements.forEach((el) => {
        const words = JSON.parse(el.dataset.words);
        const duration = parseInt(el.dataset.duration, 10) || 3000;

        let index = 0;

        const showWord = (word) => {
            const letters = word.split("");

            const wordSpan = document.createElement("span");
            wordSpan.className = "flip-word absolute inset-0 inline-block whitespace-nowrap";
            el.appendChild(wordSpan);

            // Add individual letters
            letters.forEach((letter, i) => {
                const letterSpan = document.createElement("span");
                letterSpan.innerText = letter;
                letterSpan.className = "inline-block opacity-0 blur-sm";
                wordSpan.appendChild(letterSpan);

                gsap.fromTo(letterSpan,
                    { opacity: 0, y: 10, filter: "blur(8px)" },
                    {
                        opacity: 1,
                        y: 0,
                        filter: "blur(0px)",
                        delay: i * 0.05,
                        duration: 0.3,
                        ease: "power2.out"
                    }
                );
            });

            // Animate whole word in
            gsap.fromTo(wordSpan,
                { opacity: 0, y: 10 },
                {
                    opacity: 1,
                    y: 0,
                    duration: 0.4,
                    ease: "power3.out"
                }
            );

            // Exit animation schedule
            setTimeout(() => {
                gsap.to(wordSpan, {
                    opacity: 0,
                    y: -40,
                    x: 40,
                    scale: 2,
                    filter: "blur(8px)",
                    duration: 0.5,
                    ease: "power3.in",
                    onComplete: () => {
                        wordSpan.remove();
                        index = (index + 1) % words.length;
                        showWord(words[index]);
                    }
                });
            }, duration);
        };

        // Start animation
        showWord(words[index]);
    });
});
