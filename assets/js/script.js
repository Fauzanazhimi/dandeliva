document.addEventListener("DOMContentLoaded", function () {
  // Mobile menu toggle
  const mobileBtn = document.querySelector(".mobile-menu-btn");
  const navLinks = document.querySelector(".nav-links");

  const overlay = document.createElement("div");
  overlay.className = "mobile-overlay";
  document.body.appendChild(overlay);

  if (mobileBtn && navLinks) {
    function toggleMenu() {
        navLinks.classList.toggle("active");
        overlay.classList.toggle("active");
        const icon = mobileBtn.querySelector("i");
        if(navLinks.classList.contains("active")) {
            icon.classList.remove("fa-bars");
            icon.classList.add("fa-times");
            document.body.style.overflow = "hidden";
        } else {
            icon.classList.remove("fa-times");
            icon.classList.add("fa-bars");
            document.body.style.overflow = "";
        }
    }
    
    mobileBtn.addEventListener("click", toggleMenu);
    overlay.addEventListener("click", toggleMenu);
  }

  // Add some simple animations on scroll
  const fadeElements = document.querySelectorAll(
    ".feature-card, .product-card, .article-card",
  );

  const observer = new IntersectionObserver(
    (entries) => {
      entries.forEach((entry) => {
        if (entry.isIntersecting) {
          entry.target.style.opacity = 1;
          entry.target.style.transform = "translateY(0)";
        }
      });
    },
    { threshold: 0.1 },
  );

  fadeElements.forEach((el) => {
    el.style.opacity = 0;
    el.style.transform = "translateY(20px)";
    el.style.transition = "all 0.6s ease-out";
    observer.observe(el);
  });

    // FAQs Accordion
    const faqQuestions = document.querySelectorAll('.faq-question');
    faqQuestions.forEach(question => {
        question.addEventListener('click', () => {
            const item = question.closest('.faq-item');
            const isActive = item.classList.contains('active');
            
            // Close all
            document.querySelectorAll('.faq-item').forEach(faq => {
                faq.classList.remove('active');
            });

            // Open clicked
            if (!isActive) {
                item.classList.add('active');
            }
        });
    });

    // Rewards Popup Widget
    const rewardBtn = document.getElementById('rewardsBtn');
    const rewardPopup = document.getElementById('rewardsPopup');
    const rewardClose = document.getElementById('rewardsClose');

    if (rewardBtn && rewardPopup) {
        rewardBtn.addEventListener('click', () => {
            if (rewardPopup.style.display === 'block') {
                rewardPopup.classList.remove('show');
                setTimeout(() => { rewardPopup.style.display = 'none'; }, 300);
            } else {
                rewardPopup.style.display = 'block';
                // Trigger reflow
                void rewardPopup.offsetWidth;
                rewardPopup.classList.add('show');
            }
        });

        if (rewardClose) {
            rewardClose.addEventListener('click', () => {
                rewardPopup.classList.remove('show');
                setTimeout(() => { rewardPopup.style.display = 'none'; }, 300);
            });
        }
    }
    // Wellness Dashboard Tabs Toggle
    const dashboardTabs = document.querySelectorAll('.dashboard-menu li');
    const dashboardPanels = document.querySelectorAll('.dashboard-tab');

    if (dashboardTabs.length > 0 && dashboardPanels.length > 0) {
        dashboardTabs.forEach(tab => {
            tab.addEventListener('click', () => {
                // Remove active classes
                dashboardTabs.forEach(t => t.classList.remove('active'));
                dashboardPanels.forEach(p => p.classList.remove('active'));

                // Add active class to clicked tab
                tab.classList.add('active');
                
                // Show corresponding panel
                const targetId = tab.getAttribute('data-tab');
                const targetPanel = document.getElementById(targetId);
                if (targetPanel) {
                    targetPanel.classList.add('active');
                }
            });
        });

        // Symptom Tags selection
        const symptomTags = document.querySelectorAll('.symptom-tags .tag');
        symptomTags.forEach(tag => {
            tag.addEventListener('click', function() {
                this.classList.toggle('active');
                const icon = this.querySelector('i');
                if (this.classList.contains('active')) {
                    this.style.background = 'var(--primary-color)';
                    this.style.color = 'white';
                    if (icon) { icon.classList.remove('fa-plus'); icon.classList.add('fa-check'); }
                } else {
                    this.style.background = '#f0f0f0';
                    this.style.color = 'inherit';
                    if (icon) { icon.classList.remove('fa-check'); icon.classList.add('fa-plus'); }
                }
            });
        });
    }
});
