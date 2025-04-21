import { Injectable } from '@angular/core';

@Injectable({
  providedIn: 'root',
})
export class ServiceStorageService {
  private storage: Storage;
  constructor() {
    this.storage = window.localStorage;
  }

  set(key: string, value: any): boolean {
    if (this.storage) {
      this.storage.setItem(key, JSON.stringify(value));
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

  remove(key: string): boolean {
    if (this.storage) {
      this.storage.removeItem(key);
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
