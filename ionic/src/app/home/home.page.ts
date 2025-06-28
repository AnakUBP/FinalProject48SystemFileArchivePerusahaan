import { Component, OnInit } from '@angular/core';
import { Router } from '@angular/router';
import { ActionSheetController, LoadingController } from '@ionic/angular';
import { AuthService } from '../services/auth.service';
import { CutiService } from '../services/cuti.service';

@Component({
  selector: 'app-home',
  templateUrl: 'home.page.html',
  styleUrls: ['home.page.scss'],
})
export class HomePage implements OnInit {
  currentUser: any = null;
  pengajuanList: any[] = [];
  weekDates: Date[] = [];
  currentMonthYear: string = '';
  isLoading: boolean = true;
  
  private readonly STORAGE_URL = 'http://127.0.0.1:8000/storage/';

  constructor(
    private authService: AuthService,
    private cutiService: CutiService,
    private actionSheetCtrl: ActionSheetController,
    private loadingCtrl: LoadingController,
    private router: Router
  ) {
    this.generateWeekDates();
    const today = new Date();
    this.currentMonthYear = today.toLocaleString('id-ID', { month: 'long', year: 'numeric' });
  }

  ngOnInit() {}

  // Gunakan ionViewWillEnter agar data di-refresh setiap kali halaman ini ditampilkan
  ionViewWillEnter() {
    this.loadData();
  }

  async loadData(event?: any) {
    // Hanya tampilkan loading overlay saat pertama kali, bukan saat refresh
    if (!event) {
        this.isLoading = true;
        const loading = await this.loadingCtrl.create({ message: 'Memuat data...' });
        await loading.present();
    }

    // Ambil data user yang sedang login
    this.authService.getUserProfile().subscribe(user => {
      this.currentUser = user;
    });

    // Ambil daftar pengajuan cuti
    this.cutiService.getPengajuanCuti().subscribe({
      next: (data) => {
        this.pengajuanList = data;
        this.isLoading = false;
        if(event) event.target.complete(); // Sembunyikan ikon refresher
        if(this.loadingCtrl.getTop()) this.loadingCtrl.dismiss();
      },
      error: (err) => {
        console.error('Gagal memuat daftar cuti:', err);
        this.isLoading = false;
        if(event) event.target.complete(); // Sembunyikan ikon refresher
        if(this.loadingCtrl.getTop()) this.loadingCtrl.dismiss();
      }
    });
  }

  /**
   * Fungsi ini dipanggil oleh komponen ion-refresher saat
   * pengguna menarik layar ke bawah.
   * @param event Event dari ion-refresher
   */
  handleRefresh(event: any) {
    this.loadData(event);
  }

  // --- Fungsi-fungsi lain untuk tampilan (tidak berubah) ---
  generateWeekDates() {
    const today = new Date();
    const startOfWeek = new Date(today.setDate(today.getDate() - today.getDay()));
    this.weekDates = [];
    for (let i = 0; i < 7; i++) {
      const date = new Date(startOfWeek);
      date.setDate(date.getDate() + i);
      this.weekDates.push(date);
    }
  }

  isToday(date: Date): boolean {
    const today = new Date();
    return date.getDate() === today.getDate() && 
           date.getMonth() === today.getMonth() && 
           date.getFullYear() === today.getFullYear();
  }

  async logout() {
    await this.authService.logout();
    this.router.navigateByUrl('/login', { replaceUrl: true });
  }

  getFullImageUrl(path: string): string {
    return path ? this.STORAGE_URL + path : 'https://ionicframework.com/docs/img/demos/avatar.svg';
  }
  
  getStatus(item: any): string {
    return item.surat_cuti_resmi?.status || 'diajukan';
  }

  getIconForStatus(status: string): string {
    switch (status) {
      case 'disetujui': return 'checkmark-circle';
      case 'ditolak': return 'close-circle';
      default: return 'mail';
    }
  }

  getColorForStatus(status: string): string {
     switch (status) {
      case 'disetujui': return 'success';
      case 'ditolak': return 'danger';
      default: return 'primary';
    }
  }
}
