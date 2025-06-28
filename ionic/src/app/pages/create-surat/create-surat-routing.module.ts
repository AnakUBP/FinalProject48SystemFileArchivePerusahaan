import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { CreateSuratPage } from './create-surat.page';

const routes: Routes = [
  {
    path: '',
    component: CreateSuratPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class CreateSuratPageRoutingModule {}
