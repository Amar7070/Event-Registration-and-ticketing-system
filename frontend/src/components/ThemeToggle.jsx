import React, { useState, useEffect } from 'react';
import { Sun, Moon } from 'lucide-react';

export default function ThemeToggle() {
  // Default to light mode — only use saved preference if explicitly set
  const [darkMode, setDarkMode] = useState(() => {
    const saved = localStorage.getItem('darkMode');
    // If user has explicitly saved a preference, use it. Otherwise: light mode default.
    return saved === 'true';
  });

  useEffect(() => {
    const root = document.documentElement;
    if (darkMode) {
      root.classList.add('dark');
      localStorage.setItem('darkMode', 'true');
    } else {
      root.classList.remove('dark');
      localStorage.setItem('darkMode', 'false');
    }
  }, [darkMode]);

  return (
    <button
      onClick={() => setDarkMode(prev => !prev)}
      className="p-3 bg-white dark:bg-slate-900 border border-slate-100 dark:border-slate-800 rounded-xl hover:border-primary/30 transition-all duration-300 cursor-pointer shadow-sm group"
      title={darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
      aria-label={darkMode ? 'Switch to Light Mode' : 'Switch to Dark Mode'}
    >
      {darkMode
        ? <Sun className="w-4 h-4 text-amber-400 fill-amber-400 group-hover:rotate-12 transition-transform duration-300" />
        : <Moon className="w-4 h-4 text-slate-500 group-hover:-rotate-12 transition-transform duration-300" />
      }
    </button>
  );
}
