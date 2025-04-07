import { UseFormRegisterReturn } from "react-hook-form";
import "./component.css"
type InputTypes = "text" | "file" | "hidden" | "checkbox" | "number" | "email" | "password";

interface InputProps {
    label: string;
    type: InputTypes
    placeholder?: string
    onInput?: () => void
    disabled?: boolean
    required? : boolean
    register: UseFormRegisterReturn
}

export default function Input({ label, type, onInput, disabled, placeholder, register }: InputProps) {

    return (
        <div className="input-wrapper">
            <label htmlFor={label}>{label}</label>
            <input type={type}  onInput={onInput} disabled={disabled} placeholder={placeholder} {...register} />
        </div>
    )
}