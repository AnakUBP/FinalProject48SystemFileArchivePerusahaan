import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';

import { IonicModule } from '@ionic/angular';

import { CreateSuratPageRoutingModule } from './create-surat-routing.module';

import { CreateSuratPage } from './create-surat.page';

@NgModule({
  imports: [
    CommonModule,
    FormsModule,
    IonicModule,
    CreateSuratPageRoutingModule
  ],
  declarations: [CreateSuratPage]
})
export class CreateSuratPageModule {}
