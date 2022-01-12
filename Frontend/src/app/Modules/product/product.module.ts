import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

// Components
import { ProductDetailComponent } from './Components/product-detail/product-detail.component';
import { ProductListComponent } from './Components/product-list/product-list.component';

const moduleRoutes: Routes = [
  {
    path: '',
    children : [
      {
        path: 'detail/:productId',
        component: ProductDetailComponent
      },
      {
        path: 'list',
        component: ProductListComponent
      }
    ]
  }
];

@NgModule({
  declarations: [
    ProductDetailComponent,
    ProductListComponent
  ],
  imports: [
    CommonModule,
    RouterModule.forChild(moduleRoutes)
  ]
})
export class ProductModule { }
