import fs from 'fs';
import path from 'path';
import matter from 'gray-matter';

export interface Post {
  title: string;
  date: string;
  content: string;
  slug: string;
}

const CONTENT_DIR = path.resolve('./contents');

export function getAllPosts(): Post[] {
  const files = fs.readdirSync(CONTENT_DIR);
  return files.map((filename) => {
    const slug = filename.replace(/\.md$/, '');
    const raw = fs.readFileSync(path.join(CONTENT_DIR, filename), 'utf-8');
    const { data, content } = matter(raw);
    return {
      title: data.title,
      date: data.date,
      content,
      slug,
    };
  });
}

export function getPostBySlug(slug: string): Post | null {
  const filepath = path.join(CONTENT_DIR, `${slug}.md`);
  if (!fs.existsSync(filepath)) return null;
  const raw = fs.readFileSync(filepath, 'utf-8');
  const { data, content } = matter(raw);
  return {
    title: data.title,
    date: data.date,
    content,
    slug,
  };
}
