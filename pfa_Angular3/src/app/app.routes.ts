import { Routes } from '@angular/router';
import { AdminComponent } from './theme/layout/admin/admin.component';
import { GuestComponent } from './theme/layout/guest/guest.component';

export const routes: Routes = [
  {
    path: '',
    component: AdminComponent,
    children: [
      {
        path: 'client-profile',
        children: [
          {
            path: '',
            loadComponent: () =>
              import('./pages/profile/profile.component')
                .then(c => c.ProfileComponent)
          },
          {
            path: 'edit',
            loadComponent: () =>
              import('./pages/profile/edit-profile.component')
                .then(c => c.EditProfileComponent)
          }
        ]
      },
      {
        path: 'dashboard',
        loadComponent: () =>
          import('./demo/dashboard/dashboard.component')
            .then(c => c.DashboardComponent)
      },
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' }
    ]
  },
  {
    path: '',
    component: GuestComponent,
    children: [
      {
        path: 'login',
        loadComponent: () =>
          import('./demo/pages/authentication/auth-signin/auth-signin.component')
            .then(c => c.AuthSigninComponent)
      },
      {
        path: 'register',
        loadComponent: () =>
          import('./demo/pages/authentication/auth-signup/auth-signup.component')
            .then(c => c.AuthSignupComponent)
      }
    ]
  }
];
