import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { BehaviorSubject, from, Observable, of } from 'rxjs';
import { tap , map , catchError } from 'rxjs/operators';
import { Preferences } from '@capacitor/preferences';

// Kunci untuk menyimpan token di storage
const TOKEN_KEY = 'authToken';

@Injectable({
  providedIn: 'root'
})
export class AuthService {
  // URL API Laravel Anda
  private readonly API_URL = 'https://lettera.anopus.my.id/api';

  // BehaviorSubject untuk melacak status autentikasi secara real-time
  isAuthenticated: BehaviorSubject<boolean | null> = new BehaviorSubject<boolean | null>(null);

  constructor(private http: HttpClient) {
    this.loadToken();
  }

  // Cek token saat aplikasi pertama kali dimuat
  async loadToken() {
    const token = await Preferences.get({ key: TOKEN_KEY });
    if (token && token.value) {
      this.isAuthenticated.next(true);
    } else {
      this.isAuthenticated.next(false);
    }
  }

  // Fungsi untuk login
  login(credentials: { email: string, password: string }): Observable<any> {
    return this.http.post(`${this.API_URL}/login`, credentials).pipe(
      tap(async (res: any) => {
        if (res.success && res.data.token) {
          // Jika login sukses, simpan token
          await Preferences.set({ key: TOKEN_KEY, value: res.data.token });
          this.isAuthenticated.next(true);
        }
      })
    );
  }

  getUserProfile(): Observable<any> {
    // Endpoint ini ('/profil-saya') dilindungi oleh Sanctum.
    // Asumsinya, Anda memiliki Http Interceptor yang akan menambahkan 
    // token Authorization secara otomatis ke setiap request.
    return this.http.get(`${this.API_URL}/profil-saya`).pipe(
      map((res: any) => {
        if (res.success) {
          return res.data; // Kembalikan hanya objek data user dari respons API
        }
        return null;
      })
    );
  }

  // Fungsi untuk logout
  logout(): Observable<any> {
    return this.http.post(`${this.API_URL}/logout`, {}).pipe(
      tap(async () => {
        await this.clearLocalData();
      }),
      catchError(err => {
        // Jika terjadi error, tetap hapus data lokal dan lanjutkan proses logout.
        return from(this.clearLocalData()).pipe(map(() => of(err)));
      })
    );
  }
  private async clearLocalData() {
    await Preferences.remove({ key: TOKEN_KEY });
    this.isAuthenticated.next(false);
  }

  
    forgotPassword(email: string): Observable<any> {
    return this.http.post(`${this.API_URL}/forgot-password`, { email });
  }

  resetPassword(data: any): Observable<any> {
    return this.http.post(`${this.API_URL}/reset-password`, data);
  }

}
