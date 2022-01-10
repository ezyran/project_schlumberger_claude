import { NgModule } from '@angular/core';
import { CommonModule } from '@angular/common';
import { RouterModule, Routes } from '@angular/router';

// Components
import { SignupComponent } from './Components/signup/signup.component';
import { SigninComponent } from './Components/signin/signin.component';
import { FormsModule, NgForm, ReactiveFormsModule } from '@angular/forms';

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
      }
    ]
  }
];

@NgModule({
  declarations: [
    SignupComponent,
    SigninComponent
  ],
  imports: [
    CommonModule,
    FormsModule,
    ReactiveFormsModule,
    RouterModule.forChild(moduleRoutes)
  ]
})
export class ClientModule { }
