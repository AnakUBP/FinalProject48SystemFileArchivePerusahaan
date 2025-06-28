import { Component, OnInit } from '@angular/core';
import { ActivatedRoute, Router } from '@angular/router';
import { AlertController, LoadingController } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';

// Tipe untuk mengelola state halaman
type PageState = 'enterEmail' | 'emailSent' | 'enterNewPassword' | 'success';

@Component({
  selector: 'app-forgot-password',
  templateUrl: './forgot-password.page.html',
  styleUrls: ['./forgot-password.page.scss'],
})
export class ForgotPasswordPage implements OnInit {

  currentState: PageState = 'enterEmail';
  email: string = '';
  credentials = {
    token: '',
    email: '',
    password: '',
    password_confirmation: ''
  };
  isLoading: boolean = false;

  constructor(
    private route: ActivatedRoute,
    private router: Router,
    private authService: AuthService,
    private alertCtrl: AlertController,
    private loadingCtrl: LoadingController
  ) {}

  ngOnInit() {
    // Cek apakah ada token dan email di URL
    const token = this.route.snapshot.paramMap.get('token');
    const emailFromUrl = this.route.snapshot.paramMap.get('email');
    
    if (token && emailFromUrl) {
      // Jika ada, langsung ke state untuk reset password
      this.currentState = 'enterNewPassword';
      this.credentials.token = token;
      this.credentials.email = atob(emailFromUrl); // Decode email dari base64
    }
  }

  async sendLink() {
    const loading = await this.loadingCtrl.create({ message: 'Mengirim...' });
    await loading.present();
    this.isLoading = true;

    this.authService.forgotPassword(this.email).subscribe({
      next: () => {
        this.isLoading = false;
        loading.dismiss();
        this.currentState = 'emailSent'; // Ubah tampilan ke konfirmasi email
      },
      error: async (err) => {
        this.isLoading = false;
        loading.dismiss();
        this.showAlert('Error', err.error?.message || 'Gagal mengirim link.');
      }
    });
  }

  async resetPassword() {
    if (this.credentials.password !== this.credentials.password_confirmation) {
      this.showAlert('Error', 'Password dan konfirmasi password tidak cocok.');
      return;
    }

    const loading = await this.loadingCtrl.create({ message: 'Menyimpan...' });
    await loading.present();
    this.isLoading = true;

    this.authService.resetPassword(this.credentials).subscribe({
      next: () => {
        this.isLoading = false;
        loading.dismiss();
        this.currentState = 'success'; // Ubah tampilan ke konfirmasi sukses
      },
      error: async (err) => {
        this.isLoading = false;
        loading.dismiss();
        this.showAlert('Gagal', err.error?.message || 'Gagal mereset password.');
      }
    });
  }
  
  closePage() {
    this.router.navigateByUrl('/login');
  }

  goToLogin() {
    this.router.navigateByUrl('/login');
  }

  async showAlert(header: string, message: string) {
    const alert = await this.alertCtrl.create({ header, message, buttons: ['OK'] });
    await alert.present();
  }
}
