import FontSizeDisplay from "./FontText";
import contactData from "../contact.json";

interface ContactItem {
  id: number;
  name: string;
  number: string;
  image: string;
  email: string;
}

const contacts: ContactItem[] = contactData;

function ContactInfo() {
  return (
    <>
      {contacts.map((item) => (
        <div
          className="bg-gray-800 p-4 rounded-lg flex flex-col md:flex-row md:gap-5 gap-0 border-2 border-white w-full"
          key={item.id}
        >
          <div className="flex items-center justify-center md:mb-0 mb-4">
            <img
              className="h-32 w-32 rounded-full"
              src={item.image}
              alt={item.name}
            />
          </div>
          <div className=" flex flex-col md:items-start md:justify-start items-center justify-center">
            <FontSizeDisplay sizeVariant="largebold" addedClass="py-3">
              {item.name}
            </FontSizeDisplay>
            <div className="text-justify">
              <FontSizeDisplay sizeVariant="medium">
                {item.email}
              </FontSizeDisplay>
              <FontSizeDisplay sizeVariant="medium">
                {item.number}
              </FontSizeDisplay>
            </div>
          </div>
        </div>
      ))}
    </>
  );
}

export default ContactInfo;
