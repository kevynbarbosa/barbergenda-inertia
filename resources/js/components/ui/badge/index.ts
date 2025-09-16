import type { VariantProps } from "class-variance-authority"
import { cva } from "class-variance-authority"

export { default as Badge } from "./Badge.vue"

export const badgeVariants = cva(
  "inline-flex items-center justify-center rounded-full border px-2.5 py-1 text-xs font-semibold w-fit whitespace-nowrap shrink-0 [&>svg]:size-3 gap-1 [&>svg]:pointer-events-none focus-visible:border-ring focus-visible:ring-ring/50 focus-visible:ring-[3px] aria-invalid:ring-destructive/20 dark:aria-invalid:ring-destructive/40 aria-invalid:border-destructive transition-[color,box-shadow] overflow-hidden",
  {
    variants: {
      variant: {
        default:
          "border-transparent bg-primary text-primary-foreground [a&]:hover:bg-primary/90",
        secondary:
          "border-transparent bg-secondary text-secondary-foreground [a&]:hover:bg-secondary/90",
        destructive:
         "border-transparent bg-destructive text-white [a&]:hover:bg-destructive/90 focus-visible:ring-destructive/20 dark:focus-visible:ring-destructive/40 dark:bg-destructive/60",
        outline:
          "text-foreground [a&]:hover:bg-accent [a&]:hover:text-accent-foreground",
        // Status variants
        verified:
          "border-green-700 text-green-900 dark:text-green-500 dark:bg-green-900/20",
        unverified:
          "border-orange-400 bg-orange-200 text-orange-700 dark:text-amber-500 dark:bg-amber-900/20",
        // Role variants
        admin:
          "border-purple-400 bg-purple-200 text-purple-700 dark:text-purple-500 dark:bg-purple-900/20",
        moderator:
          "border-indigo-400 bg-indigo-200 text-indigo-700 dark:text-indigo-500 dark:bg-indigo-900/20",
        user:
          "border-slate-400 bg-slate-200 text-slate-700 dark:text-slate-500 dark:bg-slate-900/20",
        role:
          "border-blue-400 bg-blue-200 text-blue-700 dark:text-blue-500 dark:bg-blue-900/20",
        // Utility variants
        success:
          "border-emerald-400 bg-emerald-200 text-emerald-700 dark:text-emerald-500 dark:bg-emerald-900/20",
        warning:
          "border-yellow-400 bg-yellow-200 text-yellow-700 dark:text-yellow-500 dark:bg-yellow-900/20",
        error:
          "border-red-400 bg-red-200 text-red-700 dark:text-red-500 dark:bg-red-900/20",
        info:
          "border-cyan-400 bg-cyan-200 text-cyan-700 dark:text-cyan-500 dark:bg-cyan-900/20",
      },
    },
    defaultVariants: {
      variant: "default",
    },
  },
)
export type BadgeVariants = VariantProps<typeof badgeVariants>
