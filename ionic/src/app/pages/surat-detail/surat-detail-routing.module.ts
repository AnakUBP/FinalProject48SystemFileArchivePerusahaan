import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

import { SuratDetailPage } from './surat-detail.page';

const routes: Routes = [
  {
    path: '',
    component: SuratDetailPage
  }
];

@NgModule({
  imports: [RouterModule.forChild(routes)],
  exports: [RouterModule],
})
export class SuratDetailPageRoutingModule {}
