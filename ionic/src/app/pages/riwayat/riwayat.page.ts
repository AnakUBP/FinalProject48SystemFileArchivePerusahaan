import { Component, OnInit } from '@angular/core';
import { CutiService } from 'src/app/services/cuti.service';
import { LoadingController } from '@ionic/angular';

@Component({
  selector: 'app-riwayat',
  templateUrl: './riwayat.page.html',
  styleUrls: ['./riwayat.page.scss'],
})
export class RiwayatPage implements OnInit {

  allPengajuan: any[] = [];
  filteredPengajuan: any[] = [];
  selectedStatus: string = 'semua';
  isLoading: boolean = false;
  searchQuery: string = '';

  constructor(
    private cutiService: CutiService,
    private loadingCtrl: LoadingController
  ) { }

  ngOnInit() {
  }

  ionViewWillEnter() {
    this.loadData();
  }

  async loadData(event?: any) {
    this.isLoading = true;
    if (!event) {
      const loading = await this.loadingCtrl.create({ message: 'Memuat riwayat...' });
      await loading.present();
    }
    
    // Asumsi Anda punya method baru di CutiService untuk mengambil SEMUA data
    this.cutiService.getPengajuanCuti().subscribe({
      next: (data) => {
        this.allPengajuan = data;
        this.applyFilters(); // Terapkan filter awal
        this.isLoading = false;
        if(event) event.target.complete();
        if(this.loadingCtrl.getTop()) this.loadingCtrl.dismiss();
      },
      error: (err) => {
        console.error('Gagal memuat riwayat:', err);
        this.isLoading = false;
        if(event) event.target.complete();
        if(this.loadingCtrl.getTop()) this.loadingCtrl.dismiss();
      }
    });
  }

  handleRefresh(event: any) {
    this.loadData(event);
  }

  getStatus(item: any): string {
    return item.surat_cuti_resmi?.status || 'diajukan';
  }

  applyFilters() {
    let tempPengajuan = [...this.allPengajuan];

    // 1. Filter berdasarkan status
    if (this.selectedStatus !== 'semua') {
      tempPengajuan = tempPengajuan.filter(
        item => this.getStatus(item) === this.selectedStatus
      );
    }
    
    // 2. Filter berdasarkan pencarian
    if (this.searchQuery && this.searchQuery.trim() !== '') {
      const query = this.searchQuery.toLowerCase();
      tempPengajuan = tempPengajuan.filter((item) => {
        const jenisCuti = item.jenis_cuti?.nama.toLowerCase() || '';
        const status = this.getStatus(item).toLowerCase();
        return jenisCuti.includes(query) || status.includes(query);
      });
    }
    
    this.filteredPengajuan = tempPengajuan;
  }
  
  filterByStatus() {
    this.applyFilters();
  }
  
  searchSurat(event: any) {
    this.searchQuery = event.target.value;
    this.applyFilters();
  }
}
