import { Injectable } from '@angular/core';
import { HttpClient } from '@angular/common/http';
import { Observable } from 'rxjs';

@Injectable({
  providedIn: 'root'
})
export class CutiService {
  // Ganti dengan URL API Laravel Anda
  private readonly API_URL = 'https://lettera.anopus.my.id/api'; 

  constructor(private http: HttpClient) { }

  // Fungsi untuk mengambil daftar pengajuan cuti
  getPengajuanCuti(): Observable<any> {
    // Asumsi Anda punya endpoint GET /api/cuti yang dilindungi Sanctum
    // Service ini tidak menangani token, token akan ditambahkan oleh Interceptor
    return this.http.get(`${this.API_URL}/cuti`);
  }
  getJenisCuti(): Observable<any> {
    // Asumsi Anda punya endpoint GET /api/jenis-cuti
    return this.http.get(`${this.API_URL}/jenis-cuti`);
  }

  // --- METHOD BARU ---
  // Mengirim data pengajuan baru ke server
  createPengajuanCuti(data: any): Observable<any> {
    // Asumsi Anda punya endpoint POST /api/cuti
    return this.http.post(`${this.API_URL}/cuti`, data);
  }
  getPengajuanDetail(id: string): Observable<any> {
    return this.http.get(`${this.API_URL}/cuti/${id}`);
  }
};
