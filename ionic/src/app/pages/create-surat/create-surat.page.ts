import { Component, OnInit, ViewChild, ElementRef, AfterViewInit } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController, LoadingController } from '@ionic/angular';
import { CutiService } from 'src/app/services/cuti.service';
import SignaturePad from 'signature_pad';

@Component({
  selector: 'app-create-surat',
  templateUrl: './create-surat.page.html',
  styleUrls: ['./create-surat.page.scss'],
})
export class CreateSuratPage implements OnInit, AfterViewInit {
  @ViewChild('signatureCanvas', { static: false }) signatureCanvas!: ElementRef;

  signaturePad!: SignaturePad;
  
  pengajuanData = {
    jenis_cuti_id: null,
    tanggal_mulai: '',
    tanggal_selesai: '',
    alasan: ''
  };

  selectedFile: File | null = null;
  selectedFileName: string | null = null;
  jenisCutiList: any[] = [];
  isLoading = false;

  constructor(
    private cutiService: CutiService,
    private router: Router,
    private alertController: AlertController,
    private loadingController: LoadingController
  ) {}

  ngOnInit() {
    this.cutiService.getJenisCuti().subscribe(
      (data) => { this.jenisCutiList = data; },
      (error) => { this.showAlert('Error', 'Gagal memuat jenis cuti.'); }
    );
  }
  
  ngAfterViewInit() {
    this.signaturePad = new SignaturePad(this.signatureCanvas.nativeElement, {
        backgroundColor: 'rgb(255, 255, 255)'
    });
  }

  clearSignature() {
    this.signaturePad.clear();
  }
  
  handleFileInput(event: any) {
    const file = event.target.files[0];
    if (file) {
      this.selectedFile = file;
      this.selectedFileName = file.name;
    }
  }

  async submitPengajuan() {
    if (this.signaturePad.isEmpty()) {
      this.showAlert('Validasi Gagal', 'Tanda tangan wajib diisi.');
      return;
    }
    
    const loading = await this.loadingController.create({ message: 'Mengirim...' });
    await loading.present();
    this.isLoading = true;

    const formData = new FormData();
    formData.append('jenis_cuti_id', this.pengajuanData.jenis_cuti_id!);
    formData.append('tanggal_mulai', this.pengajuanData.tanggal_mulai);
    formData.append('tanggal_selesai', this.pengajuanData.tanggal_selesai);
    formData.append('alasan', this.pengajuanData.alasan);
    formData.append('tanda_tangan', this.signaturePad.toDataURL('image/png'));
    
    if (this.selectedFile) {
      formData.append('lampiran', this.selectedFile, this.selectedFile.name);
    }
    
    // FIX: Menghapus blok 'for...of' yang menyebabkan error kompilasi
    
    this.cutiService.createPengajuanCuti(formData).subscribe(
      async (res) => {
        await loading.dismiss();
        this.isLoading = false;
        this.showAlertAndNavigate('Sukses', 'Pengajuan cuti Anda telah berhasil dikirim.');
      },
      async (err) => {
        await loading.dismiss();
        this.isLoading = false;
        this.showAlert('Gagal', err.error?.message || 'Terjadi kesalahan saat mengirim pengajuan.');
      }
    );
  }

  async showAlert(header: string, message: string) {
    const alert = await this.alertController.create({ header, message, buttons: ['OK'] });
    await alert.present();
  }

  async showAlertAndNavigate(header: string, message: string) {
     const alert = await this.alertController.create({
          header, message,
          buttons: [{
            text: 'OK',
            handler: () => { this.router.navigateByUrl('/home', { replaceUrl: true }); },
          }],
        });
    await alert.present();
  }
}
