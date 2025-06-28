import { Component, OnInit } from '@angular/core';
import { ActivatedRoute } from '@angular/router';
import { LoadingController } from '@ionic/angular';
import { CutiService } from 'src/app/services/cuti.service';
import { Browser } from '@capacitor/browser';
import { DomSanitizer, SafeResourceUrl } from '@angular/platform-browser';

@Component({
  selector: 'app-surat-detail',
  templateUrl: './surat-detail.page.html',
  styleUrls: ['./surat-detail.page.scss'],
})
export class SuratDetailPage implements OnInit {

  pengajuan: any = null;
  isLoading: boolean = false;
  
  // FIX: Properti baru untuk menyimpan URL pratinjau dokumen yang aman
  documentPreviewUrl: SafeResourceUrl | null = null;

  private readonly API_BASE_URL = 'https://let.necrostein.com';

  constructor(
    private route: ActivatedRoute,
    private cutiService: CutiService,
    private loadingCtrl: LoadingController,
    private sanitizer: DomSanitizer // Inject DomSanitizer
  ) { }

  ngOnInit() {
    this.loadDetail();
  }

  async loadDetail() {
    this.isLoading = true;
    const loading = await this.loadingCtrl.create({ message: 'Memuat detail...' });
    await loading.present();

    const id = this.route.snapshot.paramMap.get('id');
    if (!id) {
      this.isLoading = false;
      loading.dismiss();
      return;
    }

    this.cutiService.getPengajuanDetail(id).subscribe({
      next: (data) => {
        this.pengajuan = data;
        
        // FIX: Membuat URL pratinjau setelah data diterima
        this.generateDocumentUrl(data);
        
        this.isLoading = false;
        loading.dismiss();
      },
      error: (err) => {
        console.error(`Gagal memuat detail untuk ID: ${id}`, err);
        this.isLoading = false;
        loading.dismiss();
      }
    });
  }
  
  // FIX: Fungsi baru untuk membuat URL pratinjau Google Docs Viewer
  generateDocumentUrl(pengajuanData: any) {
    const filePath = pengajuanData?.surat_cuti_resmi?.file_hasil_path;
    if (filePath) {
      // 1. Buat URL lengkap ke file .docx di server Anda
      const fullFileUrl = `${this.API_BASE_URL}/storage/${filePath}`;
      
      // 2. Buat URL Google Docs Viewer
      const viewerUrl = `https://docs.google.com/gview?url=${encodeURIComponent(fullFileUrl)}&embedded=true`;
      
      // 3. Sanitasi URL agar aman digunakan di dalam iframe
      this.documentPreviewUrl = this.sanitizer.bypassSecurityTrustResourceUrl(viewerUrl);
    }
  }

  getStatus(item: any): string {
    return item?.surat_cuti_resmi?.status || 'diajukan';
  }

  async viewSurat() {
    if (!this.pengajuan?.surat_cuti_resmi?.file_hasil_path) return;
    const fileUrl = `${this.API_BASE_URL}/storage/${this.pengajuan.surat_cuti_resmi.file_hasil_path}`;
    await Browser.open({ url: fileUrl });
  }
}
