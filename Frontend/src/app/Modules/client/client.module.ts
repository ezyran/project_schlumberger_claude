import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

// Components
import { SignupComponent } from './Components/signup/signup.component';
import { SigninComponent } from './Components/signin/signin.component';
import { FormsModule, NgForm, ReactiveFormsModule } from '@angular/forms';
import { HttpClientModule } from '@angular/common/http';
import { DetailComponent } from './Components/detail/detail.component';
import { CartComponent } from './Components/cart/cart.component';

const moduleRoutes: Routes = [
  {
    path: '',
    children : [
      {
        path: 'signup',
        component: SignupComponent
      },
      {
        path: 'signin',
        component: SigninComponent
      },
      {
        path: 'cart',
        component: CartComponent
      },
      {
        path: 'account',
        component: DetailComponent
      }
    ]
  }
];

@NgModule({
  declarations: [
    SignupComponent,
    SigninComponent,
    DetailComponent,
    CartComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    HttpClientModule,
    RouterModule.forChild(moduleRoutes)
  ]
})
export class ClientModule { }
