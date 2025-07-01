import { Component } from '@angular/core';
import { Router } from '@angular/router';
import { AuthService } from './services/auth.service';
import { MenuController } from '@ionic/angular'; // <-- 1. Import MenuController

@Component({
  selector: 'app-root',
  templateUrl: 'app.component.html',
  styleUrls: ['app.component.scss'],
})
export class AppComponent {
  public selectedIndex = 0;
  public currentUser: any = null;
  private readonly STORAGE_URL = 'storage/';

  public appPages = [
    { title: 'Profil Saya', url: '/profile', icon: 'person-circle' },
    { title: 'Riwayat Surat', url: '/riwayat', icon: 'archive' },
  ];

  constructor(
    private router: Router,
    private authService: AuthService,
    private menuCtrl: MenuController // <-- 2. Inject MenuController
  ) {
    this.initializeApp();
  }

  initializeApp() {
    this.authService.getUserProfile().subscribe(user => {
      this.currentUser = user;
    });

    const path = window.location.pathname.split('/')[1];
    if (path !== undefined) {
      this.selectedIndex = this.appPages.findIndex(page => page.url.toLowerCase().includes(path.toLowerCase()));
    }
  }

  async logout() {
    await this.menuCtrl.close();
    this.authService.logout().subscribe({
        next: () => {
            this.router.navigateByUrl('/login', { replaceUrl: true });
        },
        error: (err) => {
            console.error('Logout gagal', err);
            this.router.navigateByUrl('/login', { replaceUrl: true });
        }
    });
  }

  getFullImageUrl(path: string): string {
    return path ? this.STORAGE_URL + path : 'https://ionicframework.com/docs/img/demos/avatar.svg';
  }
}