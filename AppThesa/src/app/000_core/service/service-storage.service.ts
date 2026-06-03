import { Injectable } from '@angular/core';
import { Subject } from 'rxjs';

@Injectable({
  providedIn: 'root',
})
export class ServiceStorageService {
  private storage: Storage;
  private storageChange$ = new Subject<{ key: string; value: any }>();

  constructor() {
    this.storage = window.localStorage;
  }

  set(key: string, value: any): boolean {
    if (this.storage) {
      this.storage.setItem(key, JSON.stringify(value));
      // Emite evento de mudança
      this.storageChange$.next({ key, value });
      return true;
    }
    return false;
  }

  get(key: string): any {
    if (!this.storage) {
      return null;
    }

    const raw = this.storage.getItem(key);
    // Se não existir nada no storage, retorna null sem parse
    if (raw === null) {
      return null;
    }

    try {
      return JSON.parse(raw);
    } catch (e) {
      return null;
    }
  }

  // Retorna um Observable que emite quando o storage muda
  getStorageChanges() {
    return this.storageChange$.asObservable();
  }

  remove(key: string): boolean {
    if (this.storage) {
      this.storage.removeItem(key);
      // Emite evento de mudança
      this.storageChange$.next({ key, value: null });
      return true;
    } else {
      console.log('Error: Storage not available ', key);
    }
    return false;
  }

  clear(): boolean {
    if (this.storage) {
      this.storage.clear();
      return true;
    }
    return false;
  }

  mathEditMode(data: any): boolean {
    if (data.editMode == 'allow' || data.editMode == 'true') {
      if (this.getEditMode() == true) {
        return true;
      } else {
        return false;
      }
    } else {
      return false;
    }
  }

  getEditMode(): boolean {
    let status = this.get('editModeLocal');
    if (status == 'true') {
      return true;
    } else {
      return false;
    }
  }

  setEditMode(value: string): boolean {
    this.set('editModeLocal', value);
    return true;
  }
}
