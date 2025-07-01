document.addEventListener('DOMContentLoaded', function () {
    const calendarsContainer = document.getElementById('calendars-container');
    if (!calendarsContainer) return;

    // Mengambil data detail cuti (tanggal => [ {name: 'Budi', foto: 'path/foto.jpg'} ])
    const leaveDetails = JSON.parse(calendarsContainer.dataset.leaveDetails || '{}');
    
    // Variabel untuk menyimpan tanggal yang sedang ditampilkan
    let displayDate = new Date();

    // Fungsi utama untuk me-render kedua kalender
    function renderCalendars() {
        const currentYear = displayDate.getFullYear();
        const currentMonth = displayDate.getMonth();
        
        generateCalendar('calendar-current-month', currentYear, currentMonth, leaveDetails);
        
        const nextMonthDate = new Date(currentYear, currentMonth + 1, 1);
        generateCalendar('calendar-next-month', nextMonthDate.getFullYear(), nextMonthDate.getMonth(), leaveDetails);
    }
    
    // Render kalender saat pertama kali halaman dimuat
    renderCalendars();

    // Menggunakan event delegation untuk menangani klik pada tombol navigasi
    calendarsContainer.addEventListener('click', function(event) {
        const target = event.target.closest('.nav-btn');
        if (!target) return;

        if (target.classList.contains('prev-month')) {
            displayDate.setMonth(displayDate.getMonth() - 1);
        } else if (target.classList.contains('next-month')) {
            displayDate.setMonth(displayDate.getMonth() + 1);
        }
        renderCalendars();
    });

});

function generateCalendar(elementId, year, month, leaveDetails = {}) {
    const container = document.getElementById(elementId);
    if (!container) return;

    const date = new Date(year, month);
    const today = new Date();
    
    const monthName = date.toLocaleString('id-ID', { month: 'long' });
    const yearNum = date.getFullYear();

    const firstDayOfMonth = new Date(year, month, 1).getDay();
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    const daysOfWeek = ['Su', 'Mo', 'Tu', 'We', 'Th', 'Fr', 'Sa'];

    // FIX: Menambahkan tombol navigasi di dalam header kalender
    let html = `
        <div class="calendar-header">
            <button class="nav-btn prev-month">&lt;</button>
            <span class="month-year">${monthName} ${yearNum}</span>
            <button class="nav-btn next-month">&gt;</button>
        </div>
        <div class="calendar-grid">
    `;

    daysOfWeek.forEach(day => {
        html += `<div class="day-name">${day}</div>`;
    });

    for (let i = 0; i < firstDayOfMonth; i++) {
        html += `<div></div>`;
    }

    for (let day = 1; day <= daysInMonth; day++) {
        let classes = 'day-cell';
        if (day === today.getDate() && month === today.getMonth() && year === today.getFullYear()) {
            classes += ' today';
        }
        
        const currentDateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        
        let leaveIndicatorsHtml = '';
        let cellTitle = '';

        if (leaveDetails[currentDateStr]) {
            classes += ' has-leave';
            cellTitle = ` title="Cuti: ${leaveDetails[currentDateStr].map(e => e.name).join(', ')}"`;

            leaveIndicatorsHtml += '<div class="leave-indicators">';
            leaveDetails[currentDateStr].slice(0, 3).forEach(employee => {
                const avatarUrl = employee.foto 
                    ? `/storage/${employee.foto}` 
                    : `https://ui-avatars.com/api/?name=${encodeURIComponent(employee.name)}&size=32&background=random`;
                leaveIndicatorsHtml += `<img src="${avatarUrl}" class="leave-avatar" alt="${employee.name}">`;
            });

            if (leaveDetails[currentDateStr].length > 3) {
                const moreCount = leaveDetails[currentDateStr].length - 3;
                leaveIndicatorsHtml += `<span class="leave-avatar more">+${moreCount}</span>`;
            }
            leaveIndicatorsHtml += '</div>';
        }
        
        html += `<div class="${classes}"${cellTitle}><span>${day}</span>${leaveIndicatorsHtml}</div>`;
    }

    html += `</div>`;
    container.innerHTML = html;
}
