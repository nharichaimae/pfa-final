import { NgModule } from '@angular/core';
import { Routes, RouterModule } from '@angular/router';

// Layouts
import { AdminComponent } from './theme/layout/admin/admin.component';
import { GuestComponent } from './theme/layout/guest/guest.component';

// Guard
import { AuthGuard } from '../app/guards/auth-guard';

const routes: Routes = [
  {
    path: '',
    component: AdminComponent,
    children: [
      { path: '', redirectTo: 'dashboard', pathMatch: 'full' },

      // Dashboard (accessible uniquement admin)
      {
        path: 'dashboard',
        loadComponent: () =>
          import('./demo/dashboard/dashboard.component')
            .then(c => c.DashboardComponent),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] } // seul admin peut accéder
      },

      // Profils
      {
        path: 'client-profile',
        loadComponent: () => import('./pages/profile/profile.component').then(c => c.ProfileComponent),
        canActivate: [AuthGuard],
        data: { roles: ['CLIENT'] } // seul client peut accéder
      },
      {
        path: 'client-profile/edit',
        loadComponent: () => import('./pages/profile/edit-profile.component').then(c => c.EditProfileComponent),
        canActivate: [AuthGuard],
        data: { roles: ['CLIENT'] }
      },
      {
        path: 'admin-profile',
        loadComponent: () => import('./pages/admin-profile/admin-profile.page').then(m => m.AdminProfilePage),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] }
      },
      {
        path: 'admin-profile-edit',
        loadComponent: () => import('./pages/admin-profile-edit/admin-profile-edit.page').then(m => m.AdminProfileEditPage),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] }
      },

      // Clients CRUD (admin only)
      {
        path: 'clients',
        loadComponent: () => import('./client/show-client/client').then(m => m.ClientComponent),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] }
      },
      {
        path: 'clients/add',
        loadComponent: () => import('./client/add-client/add-client').then(m => m.AddClientComponent),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] }
      },
      {
        path: 'clients/:id',
        loadComponent: () => import('./client/client-detaille/detail').then(m => m.ClientDetail),
        canActivate: [AuthGuard],
        data: { roles: ['ADMIN'] }
      },

      // Autres pages (tu peux ajouter AuthGuard si nécessaire)
      {
        path: 'basic',
        loadChildren: () =>
          import('./demo/ui-elements/ui-basic/ui-basic.module').then(m => m.UiBasicModule)
      },
      {
        path: 'forms',
        loadComponent: () => import('./demo/pages/form-element/form-element').then(c => c.FormElement)
      },
      {
        path: 'tables',
        loadComponent: () => import('./demo/pages/tables/tbl-bootstrap/tbl-bootstrap.component').then(c => c.TblBootstrapComponent)
      },
      {
        path: 'apexchart',
        loadComponent: () => import('./demo/pages/core-chart/apex-chart/apex-chart.component').then(c => c.ApexChartComponent)
      },
      {
        path: 'sample-page',
        loadComponent: () => import('./demo/extra/sample-page/sample-page.component').then(c => c.SamplePageComponent)
      }
    ]
  },

  // Auth (login / register)
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

@NgModule({
  imports: [RouterModule.forRoot(routes)],
  exports: [RouterModule]
})
export class AppRoutingModule {}
