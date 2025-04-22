import { Injectable } from '@angular/core';
import { Router } from '@angular/router';
import { ServiceStorageService } from './service-storage.service';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private isAuthenticated = false;
  private userKey = 'loggedUser';

  constructor(
    private router: Router,
    private serviceStorage: ServiceStorageService
  ) {}

  logoff()
    {
      this.serviceStorage.remove('apikey');
      this.serviceStorage.remove('user');
      this.serviceStorage.remove('userID');
      this.serviceStorage.remove('name');
      this.serviceStorage.remove('email');
      console.log('logoff');
    }

  setToken(token: string): void {
    this.serviceStorage.set('apikey', token);
  }

  setUser(name: string): void {
    this.serviceStorage.set('user', name);
  }

  setID(name: string): void {
    this.serviceStorage.set('userID', name);
  }

  getApikey(): string | null {
    return this.serviceStorage.get('apikey');
  }

  getUser(): Array<any> | any {
    let dt = {
      userID: this.serviceStorage.get('userID'),
      name: this.serviceStorage.get('user'),
      apikey: this.serviceStorage.get('apikey'),
    };
    if (dt.userID == null) {
      return null;
    }
    return dt;
  }
}
