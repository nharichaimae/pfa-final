import { Component, OnInit } from '@angular/core';
import { ClientService } from '../../services/client.service';
import { Router,RouterModule } from '@angular/router';
import { CommonModule } from '@angular/common';
import { FormsModule } from '@angular/forms';
import { SearchService } from '../../services/search.service';
import { ChangeDetectorRef } from '@angular/core';

interface Client {
  id: number;
  nom: string;
  prenom: string;
  email: string;
  telephone: string;
  cin: string;
  photoProfil?: string;
}
interface ClientsResponse {
  success: boolean;
  count: number;
  clients: Client[];
}

@Component({
  selector: 'app-client',
  standalone: true,
  imports: [CommonModule, FormsModule,RouterModule],
  templateUrl: './client.html',
  styleUrls: ['./client.scss'],
})
export class ClientComponent implements OnInit {
  clients: Client[] = [];
  searchText: string = '';

  constructor(
    private clientService: ClientService,
    private router: Router,
    private SearchService : SearchService,
    private cdr: ChangeDetectorRef
  ) {}

  ngOnInit(): void {
    this.loadClients();
    this.SearchService.search$.subscribe(text => {
      this.searchText = text;
    });
  }
loadClients(): void {
  this.clientService.getAllClients().subscribe({
    next: (res: ClientsResponse) => {
      if (res.success) {
        this.clients = res.clients;
        this.cdr.detectChanges(); // 🔥 important
      }
    },
    error: (err) => console.error('Erreur API:', err)
  });
}

  filteredClients(): Client[] {
    return this.clients.filter(client =>
      client.nom.toLowerCase().includes(this.searchText.toLowerCase()) ||
      client.prenom.toLowerCase().includes(this.searchText.toLowerCase()) ||
      client.email.toLowerCase().includes(this.searchText.toLowerCase())
    );
  }
  voirDetails(id: number): void {
    this.router.navigate(['/clients', id]);
  }
  supprimerClient(id: number): void {
    if (confirm('Voulez-vous vraiment supprimer ce client ?')) {
      this.clientService.deleteClient(id).subscribe({
        next: () => this.loadClients(),
        error: (err) => console.error('Erreur suppression:', err)
      });
    }
  }
}
