import "./component.css"

type ButtonTypes = "button" | "submit" | "reset"
type ButtonVariants = "primary" | 'secondary'

interface ButtonProps {
    name: string;
    type: ButtonTypes
    variant: ButtonVariants
    disabled?: boolean
    onClick?: () => void
}

export default function Button({
    name,
    type,
    variant,
    disabled,
    onClick
}: ButtonProps) {

    return (
        <button className={variant} type={type} disabled={disabled} onClick={onClick}>{name}</button>
    )
}