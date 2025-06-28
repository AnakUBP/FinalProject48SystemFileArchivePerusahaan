import { NgModule } from '@angular/core';
import { PreloadAllModules, RouterModule, Routes } from '@angular/router';
import { AuthGuard } from './guards/auth.guard';
const routes: Routes = [
  // Rute awal aplikasi sekarang mengarah ke guard auto-login
  {
    path: '',
    loadChildren: () => import('./pages/splash/splash.module').then(m => m.SplashPageModule)
  },
  {
    path: 'home',
    loadChildren: () => import('./home/home.module').then( m => m.HomePageModule),
    canLoad: [AuthGuard] // Halaman home tetap dilindungi
  },
  {
    path: 'login',
    loadChildren: () => import('./pages/login/login.module').then( m => m.LoginPageModule)
  },
  {
    path: 'profile',
    loadChildren: () => import('./pages/profile/profile.module').then( m => m.ProfilePageModule),
    canLoad: [AuthGuard]
  },
  {
    path: 'create-surat',
    loadChildren: () => import('./pages/create-surat/create-surat.module').then( m => m.CreateSuratPageModule),
  },
  {
    path: 'splash',
    loadChildren: () => import('./pages/splash/splash.module').then( m => m.SplashPageModule),
  },
  {
    path: 'calendar',
    loadChildren: () => import('./pages/calendar/calendar.module').then( m => m.CalendarViewPageModule),
  },
  {
    path: 'surat-detail',
    loadChildren: () => import('./pages/surat-detail/surat-detail.module').then( m => m.SuratDetailPageModule)
  },
  {
    path: 'surat-detail/:id',
    loadChildren: () => import('./pages/surat-detail/surat-detail.module').then( m => m.SuratDetailPageModule),
    canLoad: [AuthGuard] // Lindungi rute ini
  },
  {
    path: 'riwayat',
    loadChildren: () => import('./pages/riwayat/riwayat.module').then( m => m.RiwayatPageModule),
    canLoad: [AuthGuard] // <-- Lindungi rute ini
  },
  {
    path: 'forgot-password',
    loadChildren: () => import('./pages/forgot-password/forgot-password.module').then( m => m.ForgotPasswordPageModule)
  },
  {
    // Rute untuk menerima link reset dari email
    path: 'reset-password/:token/:email',
    loadChildren: () => import('./pages/forgot-password/forgot-password.module').then( m => m.ForgotPasswordPageModule)
  }
];

@NgModule({
  imports: [
    RouterModule.forRoot(routes, { preloadingStrategy: PreloadAllModules })
  ],
  exports: [RouterModule]
})
export class AppRoutingModule { }

