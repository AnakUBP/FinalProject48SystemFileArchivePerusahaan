import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { SuratDetailPageRoutingModule } from './surat-detail-routing.module';

import { SuratDetailPage } from './surat-detail.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    SuratDetailPageRoutingModule
  ],
  declarations: [SuratDetailPage]
})
export class SuratDetailPageModule {}
