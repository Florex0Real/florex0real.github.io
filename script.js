document.addEventListener('DOMContentLoaded', () => {
    const messages = document.querySelectorAll('.message');
    let currentIndex = 0;
    
    function showNextMessage() {
        // Önceki mesajı gizle
        if (currentIndex > 0) {
            messages[currentIndex - 1].classList.remove('active');
        }
        
        // Yeni mesajı göster
        messages[currentIndex].classList.add('active');
        
        // Sonraki mesaja geç
        currentIndex = (currentIndex + 1) % messages.length;
    }
    
    // İlk mesajı göster
    showNextMessage();
    
    // Her 3 saniyede bir mesajı değiştir
    setInterval(showNextMessage, 3000);
    
    // Sayfa yüklendiğinde kalp animasyonunu başlat
    const heart = document.querySelector('.heart');
    heart.style.animation = 'heartbeat 1.5s ease-in-out infinite';
}); 
