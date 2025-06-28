import { Injectable } from '@angular/core';
import {
  HttpRequest,
  HttpHandler,
  HttpEvent,
  HttpInterceptor
} from '@angular/common/http';
import { Observable, from } from 'rxjs';
import { switchMap } from 'rxjs/operators';
import { Preferences } from '@capacitor/preferences';

const TOKEN_KEY = 'authToken';

@Injectable()
export class AuthInterceptor implements HttpInterceptor {

  constructor() {}

  intercept(request: HttpRequest<unknown>, next: HttpHandler): Observable<HttpEvent<unknown>> {
    // Gunakan 'from' untuk mengubah Promise (dari Preferences) menjadi Observable
    return from(Preferences.get({ key: TOKEN_KEY })).pipe(
      switchMap(token => {
        // Jika token ada, kloning request dan tambahkan header Authorization
        if (token && token.value) {
          const clonedRequest = request.clone({
            setHeaders: {
              'Authorization': `Bearer ${token.value}`
            }
          });
          // Lanjutkan dengan request yang sudah dimodifikasi
          return next.handle(clonedRequest);
        }
        // Jika tidak ada token, lanjutkan dengan request asli
        return next.handle(request);
      })
    );
  }
}