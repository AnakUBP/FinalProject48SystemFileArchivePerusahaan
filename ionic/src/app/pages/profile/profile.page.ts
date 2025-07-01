import { Component, OnInit } from '@angular/core';
import { AuthService } from 'src/app/services/auth.service';

@Component({
  selector: 'app-profile',
  templateUrl: './profile.page.html',
  styleUrls: ['./profile.page.scss'],
})
export class ProfilePage implements OnInit {
  profileData: any = null;
  private readonly STORAGE_URL = 'https://lettera.anopus.my.id/storage/';

  constructor(private authService: AuthService) { }

  ngOnInit() {
    this.authService.getUserProfile().subscribe(
      (data) => {
        this.profileData = data;
      },
      (error) => {
        console.error('Gagal memuat profil:', error);
      }
    );
  }

  getFullImageUrl(path: string): string {
    return this.STORAGE_URL + path;
  }
}
