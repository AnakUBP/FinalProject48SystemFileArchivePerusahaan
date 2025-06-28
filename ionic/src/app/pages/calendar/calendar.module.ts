import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

// KUNCI UTAMA: Pastikan IonicModule di-import di sini
import { IonicModule } from '@ionic/angular';

import { CalendarPageRoutingModule } from './calendar-routing.module';

import { CalendarPage } from './calendar.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule, // Pastikan baris ini ada
    CalendarPageRoutingModule
  ],
  declarations: [CalendarPage]
})
export class CalendarViewPageModule {}
