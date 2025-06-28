import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from 'src/app/services/auth.service';
import { filter, take } from 'rxjs/operators';

@Component({
  selector: 'app-splash',
  templateUrl: './splash.page.html',
  styleUrls: ['./splash.page.scss'],
})
export class SplashPage {
  public laravelApiUrl = 'http://127.0.0.1:8000/'; 
  constructor(
    private authService: AuthService,
    private router: Router
  ) { }

  // Gunakan ionViewDidEnter agar logika ini berjalan setiap kali halaman splash ditampilkan
  ionViewDidEnter() {
    console.log("SplashPage: ionViewDidEnter_Fired"); // Untuk debugging

    // Berlangganan ke status autentikasi
    this.authService.isAuthenticated.pipe(
      // 1. filter(val => val !== null):
      //    Hanya akan melanjutkan jika status sudah dicek (bukan nilai awal null).
      filter(val => val !== null),
      // 2. take(1):
      //    Setelah mendapatkan satu nilai (true atau false), otomatis berhenti berlangganan.
      //    Ini sangat penting untuk mencegah memory leak dan redirect berulang.
      take(1)
    ).subscribe(isAuthenticated => {
      console.log("SplashPage: Authentication status received:", isAuthenticated); // Untuk debugging

      // Beri sedikit jeda agar animasi splash screen terlihat oleh pengguna
      setTimeout(() => {
        if (isAuthenticated) {
          // Jika sudah login, alihkan ke halaman beranda
          console.log("SplashPage: Redirecting to /home");
          this.router.navigateByUrl('/home', { replaceUrl: true });
        } else {
          // Jika belum login, alihkan ke halaman login
          console.log("SplashPage: Redirecting to /login");
          this.router.navigateByUrl('/login', { replaceUrl: true });
        }
      }, 1000); // Jeda 1 detik, bisa disesuaikan
    });
  }
}
