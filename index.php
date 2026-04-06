<?php 
require_once 'config/database.php';
include 'includes/header.php'; 
?>

<!-- Hero Section -->
<section class="hero">
    <div class="container">
        <div class="hero-content">
            <h1 class="hero-title">Healthy Functional Herbal Gummy for Modern Women</h1>
            <p class="hero-tagline">Menghadirkan kebaikan ekstrak herbal murni dalam bentuk gummy yang sehat, praktis, dan lezat untuk mendukung gaya hidup aktif Anda.</p>
            <div class="hero-buttons">
                <a href="shop.php" class="btn btn-primary">Beli Sekarang</a>
                <a href="#about-product" class="btn btn-outline">Pelajari Produk</a>
            </div>
        </div>
        <div class="hero-image">
            <img src="assets/images/woman_eating_gummy.png" alt="Dandeliva Herbal Gummy - Modern Healthy Woman Eating Gummy" style="width: 100%; height: auto; border-radius: 24px; box-shadow: 0 20px 40px rgba(58, 125, 68, 0.15); object-fit: cover;">
        </div>
    </div>
</section>

<!-- Keunggulan Produk -->
<section id="about-product" class="section-padding">
    <div class="container">
        <h2 class="section-title">Kenapa Memilih Dandeliva?</h2>
        <div class="features-grid">
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-leaf"></i></div>
                <h3>Natural Herbal Extract</h3>
                <p>Terbuat dari ekstrak daun dandelion 100% alami yang kaya akan antioksidan tanpa tambahan bahan kimia berbahaya.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-smile-beam"></i></div>
                <h3>Praktis & Mudah</h3>
                <p>Bentuk gummy yang kenyal dan enak, mudah dikonsumsi kapan saja dan di mana saja tanpa perlu diseduh.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-heartbeat"></i></div>
                <h3>Modern Functional Food</h3>
                <p>Lebih dari sekedar cemilan, pangan fungsional ini diformulasikan khusus untuk menjaga kesehatan wanita modern.</p>
            </div>
            <div class="feature-card">
                <div class="feature-icon"><i class="fas fa-shield-alt"></i></div>
                <h3>Food Grade & Hygienic</h3>
                <p>Diproses dengan standar keamanan pangan tertinggi dan dikemas secara higienis untuk menjaga kualitas.</p>
            </div>
        </div>
    </div>
</section>

<!-- Edukasi Singkat -->
<section class="education-intro section-padding">
    <div class="container">
        <div class="edu-content">
            <div class="edu-image">
                <img src="https://images.unsplash.com/photo-1540420773420-3366772f4999?auto=format&fit=crop&q=80&w=600" alt="Healthy Lifestyle" style="width:100%; border-radius:16px; box-shadow:0 10px 15px rgba(0,0,0,0.1);">
            </div>
            <div class="edu-text">
                <h2 class="section-title" style="text-align:left; margin-bottom:20px;">Apa Itu Pangan Fungsional?</h2>
                <p>Pangan fungsional adalah makanan yang secara alami maupun telah melalui proses pengolahan, mengandung satu atau lebih senyawa yang mempunyai fungsi fisiologis tertentu yang bermanfaat bagi kesehatan.</p>
                <p><strong>Manfaat Herbal dalam Dandeliva:</strong> Daun dandelion dikenal baik dalam membantu proses detoksifikasi, memelihara fungsi hati, menjaga kesehatan pencernaan, dan bagus untuk menyeimbangkan hormon wanita.</p>
                <a href="education.php" class="btn btn-outline mt-4">Baca Artikel Edukasi</a>
            </div>
        </div>
    </div>
</section>

<!-- Video Edukasi -->
<section class="section-padding">
    <div class="container">
        <h2 class="section-title">Edukasi Herbal & Gaya Hidup Sehat</h2>
        <div style="max-width:800px; margin:0 auto;">
            <div class="video-container">
                <iframe src="https://www.youtube.com/embed/dQw4w9WgXcQ" title="Edukasi Herbal" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture" allowfullscreen></iframe>
            </div>
            <p class="text-center mt-4 text-light">Lihat bagaimana Dandeliva diolah dari tumbuhan alami hingga menjadi gummy sehat siap konsumsi.</p>
        </div>
    </div>
</section>

<!-- FAQ Section -->
<section class="section-padding" style="background-color: var(--secondary-color);">
    <div class="container">
        <div class="text-center mb-4">
            <h2 class="section-title">Frequently Asked Questions</h2>
            <p class="text-light" style="max-width:600px; margin:0 auto 40px;">Pertanyaan umum seputar produk herbal Dandeliva Gummy.</p>
        </div>
        
        <div class="faq-bot-container" style="max-width: 750px; margin: 0 auto; background: white; border-radius: 24px; box-shadow: var(--shadow-lg); overflow: hidden; display: flex; flex-direction: column; height: 550px;">
            <!-- Bot Header -->
            <div style="background: var(--primary-color); color: white; padding: 20px 25px; display: flex; align-items: center; gap: 15px; border-bottom: 1px solid rgba(255,255,255,0.1);">
                <div style="width: 50px; height: 50px; background: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; box-shadow: 0 4px 10px rgba(0,0,0,0.1);">
                    <i class="fas fa-robot" style="color: var(--primary-color); font-size: 1.6rem;"></i>
                </div>
                <div>
                    <h3 style="color: white; margin: 0; font-size: 1.3rem;">Dandeliva AI Bot <i class="fas fa-check-circle" style="color:#2ecc71; font-size:1rem; margin-left:5px;"></i></h3>
                    <span style="font-size: 0.85rem; opacity: 0.9; display:flex; align-items:center; gap:5px;"><span style="display:inline-block; width:8px; height:8px; background-color:#2ecc71; border-radius:50%;"></span> Online - Siap membantu Anda</span>
                </div>
            </div>
            
            <!-- Chat Area -->
            <div id="chatArea" style="flex: 1; padding: 25px; overflow-y: auto; background: #fdfdfd; display: flex; flex-direction: column; gap: 20px;">
                <!-- Initial Bot Message -->
                <div style="display: flex; gap: 15px; align-self: flex-start; max-width: 85%;">
                    <div style="width: 40px; height: 40px; background: var(--primary-light); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div style="background: white; padding: 15px 20px; border-radius: 0 20px 20px 20px; border: 1px solid #eee; box-shadow: var(--shadow-sm); font-size: 0.95rem; color: #444; line-height:1.6;">
                        Halo Sist! 👋 Kenalin saya Asisten Cerdas Dandeliva. Ada yang bisa saya bantu atau jelaskan seputar produk sehat gummy kami? Pilih aja pertanyaan di bawah ini langsung ya! ✨
                    </div>
                </div>
            </div>
            
            <!-- Input/Action Area -->
            <div style="padding: 20px 25px; background: white; border-top: 1px solid #f0f0f0;">
                <p style="font-size: 0.85rem; color: #888; margin-bottom: 10px; font-weight:500;">Opsi Pertanyaan Cepat:</p>
                <div style="display: flex; flex-wrap: wrap; gap: 10px;" id="quickQuestions">
                    <!-- Filled via JS -->
                </div>
            </div>
        </div>

        <script>
            const faqs = [
                {
                    q: "Apakah aman konsumsi Dandeliva setiap hari?",
                    a: "Ya, sangat aman! Dandeliva terbuat dari ekstrak herbal alami dengan dosis yang sudah terukur secara klinis. Produk ini memang diformulasikan sebagai pangan fungsional penunjang kualitas kesehatan kamu setiap hari tanpa efek ketergantungan sama sekali. 🌿"
                },
                {
                    q: "Berapa anjuran dosis konsumsi hariannya?",
                    a: "Kami sangat merekomendasikan **1-2 gummy per hari**. Idealnya, kamu bisa konsumsi di pagi hari setelah sarapan untuk mendapatkan suntikan asupan energi, mood booster, serta daya tahan tubuh maksimal di seharian penuh aktivitas kamu! ☀️"
                },
                {
                    q: "Wah, bumil dan busui boleh konsumsi ngga?",
                    a: "Meskipun Dandeliva berbahan dasar 100% herbal alami, tapi khusus untuk calon ibunda yang sedang hamil atau menyusui, kami amat sarankan buat selalu konsultasi ke dokter kandungan kamu terlebih dulu ya sebelum makan suplemen atau produk herbal apa pun demi keamanan si kecil! 👶❤️"
                },
                {
                    q: "Berapa lama Expired Date (masa simpannya)?",
                    a: "Tenang saja! Produk kita dirancang bisa bertahan optimal lho hingga **18 bulan** sejak tanggal produksi asalkan disimpan dalam kemasan botol segel rapat dan suhu ruang. Namun, setelah segel dibuka perdana, ada baiknya segera dihabiskan dalam rentang waktu terburuk **45 hari** untuk ngejaga kualitas kenyalnya. 🗓️"
                }
            ];

            const chatArea = document.getElementById('chatArea');
            const quickQuestions = document.getElementById('quickQuestions');

            function appendMessage(sender, text) {
                const msgDiv = document.createElement('div');
                msgDiv.style.display = 'flex';
                msgDiv.style.gap = '15px';
                msgDiv.style.maxWidth = '85%';
                msgDiv.style.animation = 'messageFadeIn 0.4s cubic-bezier(0.175, 0.885, 0.32, 1.275) forwards';

                if(sender === 'user') {
                    msgDiv.style.alignSelf = 'flex-end';
                    msgDiv.style.flexDirection = 'row-reverse';
                    msgDiv.innerHTML = `
                        <div style="width: 40px; height: 40px; background: var(--primary-color); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-user"></i>
                        </div>
                        <div style="background: var(--primary-color); color: white; padding: 15px 20px; border-radius: 20px 0 20px 20px; box-shadow: var(--shadow-sm); font-size: 0.95rem; line-height: 1.5;">
                            ${text}
                        </div>
                    `;
                } else {
                    msgDiv.style.alignSelf = 'flex-start';
                    // Parse markdown-like bold syntax
                    let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
                    msgDiv.innerHTML = `
                        <div style="width: 40px; height: 40px; background: var(--primary-light); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                            <i class="fas fa-robot"></i>
                        </div>
                        <div style="background: white; padding: 15px 20px; border-radius: 0 20px 20px 20px; border: 1px solid #eee; box-shadow: var(--shadow-sm); font-size: 0.95rem; color: #444; line-height: 1.6;">
                            ${formattedText}
                        </div>
                    `;
                }
                
                chatArea.appendChild(msgDiv);
                chatArea.scrollTo({ top: chatArea.scrollHeight, behavior: 'smooth' });
            }

            function askBot(index) {
                const item = faqs[index];
                
                // Add user message
                appendMessage('user', item.q);
                
                // Disable buttons temporarily
                Array.from(quickQuestions.children).forEach(btn => {
                    btn.disabled = true;
                    btn.style.opacity = '0.5';
                    btn.style.cursor = 'not-allowed';
                });
                
                // Show typing indicator
                const typingId = 'typing-' + Date.now();
                const typingDiv = document.createElement('div');
                typingDiv.id = typingId;
                typingDiv.style.display = 'flex';
                typingDiv.style.gap = '15px';
                typingDiv.style.alignSelf = 'flex-start';
                typingDiv.style.maxWidth = '85%';
                typingDiv.style.animation = 'messageFadeIn 0.3s forwards';
                typingDiv.innerHTML = `
                    <div style="width: 40px; height: 40px; background: var(--primary-light); color: white; border-radius: 50%; display: flex; align-items: center; justify-content: center; flex-shrink: 0; box-shadow: var(--shadow-sm);">
                        <i class="fas fa-robot"></i>
                    </div>
                    <div style="background: white; padding: 15px 25px; border-radius: 0 20px 20px 20px; border: 1px solid #eee; box-shadow: var(--shadow-sm); display: flex; align-items: center; gap: 5px;">
                        <div class="dot-typing"></div><div class="dot-typing" style="animation-delay: 0.2s"></div><div class="dot-typing" style="animation-delay: 0.4s"></div>
                    </div>
                `;
                chatArea.appendChild(typingDiv);
                chatArea.scrollTo({ top: chatArea.scrollHeight, behavior: 'smooth' });

                // Simulate processing delay based on text length
                const thinkTime = Math.max(1000, Math.min(2500, item.a.length * 15));

                setTimeout(() => {
                    // Remove typing indicator
                    const tDiv = document.getElementById(typingId);
                    if(tDiv) tDiv.remove();
                    
                    // Add AI Response
                    appendMessage('bot', item.a);
                    
                    // Re-enable buttons except the one just clicked
                    Array.from(quickQuestions.children).forEach((btn, idx) => {
                        if (idx !== index) {
                            btn.disabled = false;
                            btn.style.opacity = '1';
                            btn.style.cursor = 'pointer';
                        }
                    });
                }, thinkTime);
            }

            // Initialize buttons
            faqs.forEach((faq, index) => {
                const btn = document.createElement('button');
                btn.onclick = () => askBot(index);
                btn.innerHTML = `<span>${faq.q}</span> <i class="fas fa-arrow-right" style="font-size:0.75rem; margin-top:2px;"></i>`;
                btn.className = 'bot-quick-btn';
                quickQuestions.appendChild(btn);
            });

            // Inject Custom Styles
            const style = document.createElement('style');
            style.innerHTML = `
                @keyframes messageFadeIn {
                    from { opacity: 0; transform: translateY(15px) scale(0.95); }
                    to { opacity: 1; transform: translateY(0) scale(1); }
                }
                .dot-typing {
                    width: 6px; height: 6px;
                    background-color: #aaa;
                    border-radius: 50%;
                    animation: bounce 1.4s infinite ease-in-out both;
                }
                @keyframes bounce {
                    0%, 80%, 100% { transform: scale(0); }
                    40% { transform: scale(1); }
                }
                .bot-quick-btn {
                    background: transparent;
                    border: 1px solid var(--border-color);
                    color: var(--text-color);
                    padding: 10px 18px;
                    border-radius: 30px;
                    font-size: 0.9rem;
                    cursor: pointer;
                    transition: all 0.3s ease;
                    display: flex;
                    align-items: center;
                    gap: 10px;
                    font-family: inherit;
                    background: #fcfcfc;
                }
                .bot-quick-btn:hover {
                    background: var(--primary-color);
                    border-color: var(--primary-color);
                    color: white;
                    transform: translateY(-2px);
                    box-shadow: 0 4px 8px rgba(58, 125, 68, 0.2);
                }
                #chatArea::-webkit-scrollbar { width: 6px; }
                #chatArea::-webkit-scrollbar-track { background: transparent; }
                #chatArea::-webkit-scrollbar-thumb { background: #ddd; border-radius: 10px; }
                #chatArea::-webkit-scrollbar-thumb:hover { background: #bbb; }
            `;
            document.head.appendChild(style);
        </script>
    </div>
</section>

<!-- CTA Section -->
<section class="cta-section section-padding">
    <div class="container">
        <h2>Siap Memulai Gaya Hidup Sehat?</h2>
        <p>Rasakan manfaat ekstrak daun dandelion dalam setiap gigitan kenyal Dandeliva Gummy.</p>
        <a href="shop.php" class="btn btn-outline" style="background:#fff; color:#3A7D44;">Beli Produk Sekarang</a>
    </div>
</section>

<?php include 'includes/footer.php'; ?>
