import { Component, OnInit } from '@angular/core';
import { LoadingController } from '@ionic/angular';
import { CutiService } from 'src/app/services/cuti.service';
import { AuthService } from '../../services/auth.service';
@Component({
  selector: 'app-calendar-view',
  templateUrl: './calendar.page.html',
  styleUrls: ['./calendar.page.scss'],
})
export class CalendarPage implements OnInit {
  currentUser: any = null;
  
  // Properti untuk mengelola kalender
  currentDate: Date = new Date();
  currentMonthName: string = '';
  currentYear: number = 0;
  days: (number | null)[] = [];
  daysOfWeek = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];
  
  // Properti untuk mengelola data agenda
  allAgenda: any[] = [];
  filteredAgenda: any[] = [];
  selectedDay: number | null = null;
  agendaTitle: string = 'Agenda Bulan Ini';
  isLoading: boolean = false;

  // Set untuk menyimpan tanggal cuti yang disetujui (format: YYYY-MM-DD)
  private approvedLeaveDates = new Set<string>();
  
  private readonly STORAGE_URL = 'https://lettera.anopus.my.id/storage/';

  constructor(
    private cutiService: CutiService,
    private loadingCtrl: LoadingController,
    private authService: AuthService,
  ) {}

  ngOnInit() {
    this.generateCalendar();
    this.loadLeaveData();
    this.authService.getUserProfile().subscribe(user => {
      this.currentUser = user;
    });
  }

  // Membuat struktur grid untuk bulan saat ini
  generateCalendar(): void {
    const year = this.currentDate.getFullYear();
    const month = this.currentDate.getMonth();
    this.currentMonthName = this.currentDate.toLocaleString('id-ID', { month: 'long' });
    this.currentYear = year;

    const firstDayOfMonth = new Date(year, month, 1).getDay(); // 0=Sunday, 1=Monday...
    const daysInMonth = new Date(year, month + 1, 0).getDate();

    this.days = Array(firstDayOfMonth).fill(null); // Sel kosong sebelum tanggal 1
    for (let i = 1; i <= daysInMonth; i++) {
      this.days.push(i);
    }
  }

  // Mengambil semua data cuti dari server
  async loadLeaveData(): Promise<void> {
    this.isLoading = true;
    const loading = await this.loadingCtrl.create({ message: 'Memuat data...' });
    await loading.present();

    this.cutiService.getPengajuanCuti().subscribe({
      next: (data) => {
        this.allAgenda = data;
        this.processLeaveDates(data); // Proses untuk menandai tanggal di kalender
        this.selectDate(null); // Tampilkan agenda bulan ini secara default
        this.isLoading = false;
        loading.dismiss();
      },
      error: (err) => {
        console.error('Gagal memuat data cuti:', err);
        this.isLoading = false;
        loading.dismiss();
      }
    });
  }

  // Memproses data untuk menandai tanggal cuti yang disetujui
  processLeaveDates(data: any[]): void {
    this.approvedLeaveDates.clear();
    const approvedLeaves = data.filter(item => this.getStatus(item) === 'disetujui');
    approvedLeaves.forEach((leave: any) => {
      let currentDate = new Date(leave.tanggal_mulai);
      const endDate = new Date(leave.tanggal_selesai);
      while (currentDate <= endDate) {
        this.approvedLeaveDates.add(this.formatDateForCheck(currentDate));
        currentDate.setDate(currentDate.getDate() + 1);
      }
    });
  }

  // Fungsi untuk navigasi ke bulan sebelumnya
  previousMonth(): void {
    this.currentDate.setMonth(this.currentDate.getMonth() - 1);
    this.generateCalendar();
    this.selectDate(null); // Reset filter saat ganti bulan
  }

  // Fungsi untuk navigasi ke bulan berikutnya
  nextMonth(): void {
    this.currentDate.setMonth(this.currentDate.getMonth() + 1);
    this.generateCalendar();
    this.selectDate(null); // Reset filter saat ganti bulan
  }

  // Fungsi yang dijalankan saat pengguna mengklik sebuah tanggal
  selectDate(day: number | null): void {
    this.selectedDay = day;

    if (day === null) {
      // Jika tidak ada tanggal yang dipilih, tampilkan semua agenda untuk bulan ini
      const currentMonth = this.currentDate.getMonth();
      const currentYear = this.currentDate.getFullYear();
      this.filteredAgenda = this.allAgenda.filter(item => {
        const itemStartDate = new Date(item.tanggal_mulai);
        return itemStartDate.getMonth() === currentMonth && itemStartDate.getFullYear() === currentYear;
      });
      this.agendaTitle = `Agenda Bulan ${this.currentMonthName}`;
    } else {
      // Filter agenda untuk tanggal yang dipilih
      const selectedTime = new Date(this.currentYear, this.currentDate.getMonth(), day).setHours(0,0,0,0);
      this.filteredAgenda = this.allAgenda.filter(item => {
        const startDate = new Date(item.tanggal_mulai).setHours(0,0,0,0);
        const endDate = new Date(item.tanggal_selesai).setHours(0,0,0,0);
        return selectedTime >= startDate && selectedTime <= endDate;
      });
      this.agendaTitle = `Agenda pada ${day} ${this.currentMonthName}`;
    }
  }
  
  // --- Helper Functions untuk Tampilan ---
  isToday = (day: number | null): boolean => day !== null && new Date().getDate() === day && new Date().getMonth() === this.currentDate.getMonth() && new Date().getFullYear() === this.currentYear;
  isLeaveDate = (day: number | null): boolean => day !== null && this.approvedLeaveDates.has(this.formatDateForCheck(new Date(this.currentYear, this.currentDate.getMonth(), day)));
  isDateSelected = (day: number | null): boolean => day !== null && day === this.selectedDay;
  getStatus = (item: any): string => item.surat_cuti_resmi?.status || 'diajukan';
  
  private formatDateForCheck = (date: Date): string => date.toISOString().split('T')[0];
  getFullImageUrl = (path: string): string => path ? `${this.STORAGE_URL}${path}` : 'https://ionicframework.com/docs/img/demos/avatar.svg';
}
