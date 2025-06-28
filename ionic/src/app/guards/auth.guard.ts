import { Injectable } from '@angular/core';
import { CanLoad, Router } from '@angular/router';
import { Observable } from 'rxjs';
import { filter, map, take } from 'rxjs/operators';
import { AuthService } from '../services/auth.service';

@Injectable({
  providedIn: 'root'
})
export class AuthGuard implements CanLoad {

  constructor(private authService: AuthService, private router: Router) { }

  canLoad(): Observable<boolean> {
    return this.authService.isAuthenticated.pipe(
      filter(val => val !== null), // Hanya lanjut jika status sudah dicek (bukan null)
      take(1), // Ambil nilai pertama lalu unsubscribe
      map(isAuthenticated => {
        if (isAuthenticated) {
          return true; // Izinkan akses ke rute
        } else {
          // Jika tidak login, arahkan ke halaman login
          this.router.navigateByUrl('/login');
          return false;
        }
      })
    );
  }
}
