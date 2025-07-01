import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AlertController, LoadingController } from '@ionic/angular';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-login',
  templateUrl: './login.page.html',
  styleUrls: ['./login.page.scss'],
})
export class LoginPage {
  public laravelApiUrl = 'https://lettera.anopus.my.id/'; 
  credentials = {
    email: '',
    password: ''
  };
  isLoading = false;

  constructor(
    private authService: AuthService,
    private router: Router,
    private alertController: AlertController,
    private loadingController: LoadingController
  ) { }

  async login() {
    const loading = await this.loadingController.create();
    await loading.present();
    this.isLoading = true;

    this.authService.login(this.credentials).subscribe(
      async (res) => {
        await loading.dismiss();
        this.isLoading = false;
        this.router.navigateByUrl('/home', { replaceUrl: true });
      },
      async (err) => {
        await loading.dismiss();
        this.isLoading = false;
        const alert = await this.alertController.create({
          header: 'Login Gagal',
          message: err.error.message || 'Email atau password salah.',
          buttons: ['OK'],
        });
        await alert.present();
      }
    );
  }
}