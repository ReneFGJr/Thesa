import { Injectable } from '@angular/core';
import { Router } from '@angular/router';

@Injectable({
  providedIn: 'root',
})
export class AuthService {
  private isAuthenticated = false;
  private userKey = 'loggedUser';

  constructor(private router: Router) {}

  login(email: string, password: string): boolean {
    const user = localStorage.getItem(email);
    if (user && JSON.parse(user).password === password) {
      localStorage.setItem(this.userKey, email);
      this.isAuthenticated = true;
      return true;
    }
    return false;
  }

  signup(email: string, password: string): boolean {
    if (localStorage.getItem(email)) return false;
    localStorage.setItem(email, JSON.stringify({ email, password }));
    return true;
  }

  logout(): void {
    localStorage.removeItem(this.userKey);
    this.isAuthenticated = false;
    this.router.navigate(['/login']);
  }

  isLoggedIn(): boolean {
    return !!localStorage.getItem(this.userKey);
  }

  resetPassword(email: string): boolean {
    return !!localStorage.getItem(email);
  }
}
